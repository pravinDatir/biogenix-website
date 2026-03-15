<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product\ProductVariant;
use App\Services\Product\ProductService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Product;
use Throwable;

class ProductController extends Controller
{
    // This renders the public product listing page.
    public function index(Request $request, ProductService $productService): View
    {
        try {
            // Step 1: read the first available search input key.
            $search = null;
            foreach (['search_text', 'search_value', 'search'] as $searchKey) {
                if ($request->filled($searchKey)) {
                    $search = trim((string) $request->input($searchKey));
                    break;
                }
            }

            Log::info('Product search initiated', ['search' => $search]);

            // Step 2: normalize the current storefront catalog filters.
            $catalogFilters = $request->query();
            $catalogFilters['search'] = $search;

            Log::info('Product filters', [
                'category_name' => $request->input('category_name'),
                'application_name' => $request->input('application_name', $request->input('subcategory_name')),
                'brand_name' => $request->input('brand_name'),
                'category' => $request->input('category', $request->input('category_id')),
                'subcategory' => $request->input('subcategory', $request->input('subcategory_id')),
                'max_price' => $request->input('max_price'),
                'sort' => $request->input('sort'),
            ]);

            // Step 3: load the catalog products and sidebar options for the current user.
            $user = $request->user();
            $catalogData = $productService->getProductListToBeDisplayed($user, $catalogFilters);

            // Step 4: track user browsing activity.
            $productService->logUserActivity($user, $request->session()->getId(), $request->path(), 'product_browse', [
                'search' => $search,
                'filters' => [
                    'category_name' => $request->input('category_name', []),
                    'application_name' => $request->input('application_name', $request->input('subcategory_name', [])),
                    'brand_name' => $request->input('brand_name', []),
                    'category' => $request->input('category', $request->input('category_id')),
                    'subcategory' => $request->input('subcategory', $request->input('subcategory_id')),
                    'max_price' => $request->input('max_price'),
                    'sort' => $request->input('sort', 'relevant'),
                ],
            ]);

            Log::info('productController.index Product search results Is:', [$catalogData['products']]);
            return view('prelogin.products', [
                'products' => $catalogData['products'],
                'catalogOptions' => $catalogData['catalogOptions'],
            ]);
        } catch (Throwable $exception) {
           
            Log::error('Failed to load product index.', ['error' => $exception->getMessage()]);
            return $this->viewWithError('prelogin.products', [
                'products' => new LengthAwarePaginator([], 0, 15),
                'catalogOptions' => [
                    'categoryOptions' => collect(),
                    'applicationOptions' => collect(),
                    'brandOptions' => collect(),
                    'minPrice' => 150,
                    'maxPrice' => 2500,
                ],
            ], $exception, 'Unable to load products right now.');
        }
    }

    // This renders the public product details page.
    public function productDetails(int $productId, Request $request, ProductService $productService): View
    {
        try {
            // Step 1: load the visible product details for the user.
            $user = $request->user();
            $product = $productService->getAccessibleProductByProductId($user, $productId);

            abort_if(! $product, 404);

            $stockStatus = 'Out of Stock';
            if ($product->visible_variant_id) {
                $visibleVariant = ProductVariant::query()
                    ->select(['stock_quantity', 'min_order_quantity'])
                    ->find($product->visible_variant_id);

                if ($visibleVariant) {
                    if ((int) $visibleVariant->stock_quantity > ((int) $visibleVariant->min_order_quantity * 10)) {
                        $stockStatus = 'Limited Availability';
                    } elseif ((int) $visibleVariant->stock_quantity >= (int) $visibleVariant->min_order_quantity) {
                        $stockStatus = 'In Stock';
                    }
                }
            }

            $product->stock_status = $stockStatus;

            $relatedProducts = $productService->frequentlyBoughtTogetherProducts($productId, $user);

            // Step 2: track user product view activity.
            $productService->logUserActivity($user,$request->session()->getId(), $request->path(), 'product_view', [
                    'product_id' => $productId ]);

            Log::info('productController.productDetails Product details:', [$product]);
            return view('prelogin.product-details', [
                'id' => $productId,
                'product' => $product,
                'related_products' => $relatedProducts,
                'gst_rate' => (float) ($product->gst_rate ?? 0),
                'tax_amount' => $product->tax_amount ?? null,
                'price_with_gst' => $product->price_with_gst ?? null,
            ]);
        } catch (Throwable $exception) {
            Log::error('Failed to load product details.', ['product_id' => $productId, 'error' => $exception->getMessage()]);

            return $this->viewWithError('prelogin.product-details', [ 'id' => $productId ], $exception, 'Unable to load product details.');
        }
    }

    // This renders the product CRUD page and optionally opens one product in edit mode.
    public function getProductById(Request $request, ProductService $productService): View
    {
        try {
            // Step 1: read the requested edit product id from query params.
            $editProductId = $request->filled('edit_product_id')
                ? (int) $request->input('edit_product_id')
                : null;

            return view('product.ProductCrud', $productService->productCrudPageData($editProductId));
        } catch (Throwable $exception) {
            Log::error('Failed to load product CRUD edit page.', ['error' => $exception->getMessage()]);

            return $this->viewWithError('product.ProductCrud', [
                'categories' => collect(),
                'subcategories' => collect(),
                'products' => new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15),
                'editingProduct' => null,
                'editingImages' => collect(),
                'editingVariants' => collect(),
            ], $exception, 'Unable to load product form.');
        }
    }

    // This renders the empty product CRUD page.
    public function showCrudProduct(ProductService $productService): View
    {
        try {
            return view('product.ProductCrud', $productService->productCrudPageData());
        } catch (Throwable $exception) {
            Log::error('Failed to load product CRUD page.', ['error' => $exception->getMessage()]);

            return $this->viewWithError('product.ProductCrud', [
                'categories' => collect(),
                'subcategories' => collect(),
                'products' => new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15),
                'editingProduct' => null,
                'editingImages' => collect(),
                'editingVariants' => collect(),
            ], $exception, 'Unable to load product page.');
        }
    }

    // This stores a new product from the CRUD form.
    public function addProduct(Request $request, ProductService $productService): RedirectResponse
    {
        try {
            // Step 1: validate the submitted product form.
            $validated = $this->validateCrudPayload($request);

            // Step 2: create the product and redirect to edit mode.
            $productId = $productService->createProductCrud($validated, $request->file('images', []));

            return redirect('/products-crud?edit_product_id='.$productId)
                ->with('status', 'Product created successfully.');
        } catch (Throwable $exception) {
            Log::error('Failed to add product.', ['error' => $exception->getMessage()]);

            return $this->redirectBackWithError($exception, 'Unable to create product.');
        }
    }

    // This updates one product from the CRUD form.
    public function updateProductById(int $productId, Request $request, ProductService $productService): RedirectResponse
    {
        try {
            // Step 1: validate the submitted product form.
            $validated = $this->validateCrudPayload($request);

            // Step 2: save the updated product data.
            $productService->updateProductCrud($productId, $validated, $request->file('images', []));

            return redirect('/products-crud?edit_product_id='.$productId)
                ->with('status', 'Product updated successfully.');
        } catch (Throwable $exception) {
            Log::error('Failed to update product.', ['product_id' => $productId, 'error' => $exception->getMessage()]);

            return $this->redirectBackWithError($exception, 'Unable to update product.');
        }
    }

    // This deletes one product from the CRUD page.
    public function deleteProductById(int $productId, ProductService $productService): RedirectResponse
    {
        try {
            // Step 1: delete the selected product and its related rows.
            $productService->deleteProductCrud($productId);

            return redirect('/products-crud')
                ->with('status', 'Product deleted successfully.');
        } catch (Throwable $exception) {
            Log::error('Failed to delete product.', ['product_id' => $productId, 'error' => $exception->getMessage()]);

            return $this->redirectBackWithError($exception, 'Unable to delete product.');
        }
    }

    // This validates the product CRUD form payload.
    protected function validateCrudPayload(Request $request): array
    {
        try {
            // Step 1: validate the main product and nested variant fields.
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'slug' => ['nullable', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
                'category_id' => ['nullable', 'integer', 'exists:categories,id'],
                'subcategory_id' => ['nullable', 'integer', 'exists:subcategories,id'],
                'base_sku' => ['nullable', 'string', 'max:120'],
                'is_published' => ['nullable', 'boolean'],

                'images' => ['nullable', 'array', 'max:10'],
                'images.*' => ['file', 'image', 'max:20480'],
                'delete_image_ids' => ['nullable', 'array'],
                'delete_image_ids.*' => ['integer'],
                'primary_image_id' => ['nullable', 'integer'],

                'variant_sku' => ['nullable', 'array'],
                'variant_sku.*' => ['nullable', 'string', 'max:120'],
                'variant_id' => ['nullable', 'array'],
                'variant_id.*' => ['nullable', 'integer', 'min:1'],
                'variant_delete' => ['nullable', 'array'],
                'variant_delete.*' => ['nullable', Rule::in(['0', '1', 0, 1])],
                'variant_name' => ['nullable', 'array'],
                'variant_name.*' => ['nullable', 'string', 'max:120'],
                'variant_attributes_json' => ['nullable', 'array'],
                'variant_attributes_json.*' => ['nullable', 'string', 'max:2000'],
                'variant_min_order_quantity' => ['nullable', 'array'],
                'variant_min_order_quantity.*' => ['nullable', 'integer', 'min:1'],
                'variant_max_order_quantity' => ['nullable', 'array'],
                'variant_max_order_quantity.*' => ['nullable', 'integer', 'min:1'],
                'variant_model_number' => ['nullable', 'array'],
                'variant_model_number.*' => ['nullable', 'string', 'max:120'],
                'variant_catalog_number' => ['nullable', 'array'],
                'variant_catalog_number.*' => ['nullable', 'string', 'max:120'],
                'variant_price' => ['nullable', 'array'],
                'variant_price.*' => ['nullable', 'numeric', 'min:0'],
                'variant_public_price' => ['nullable', 'array'],
                'variant_public_price.*' => ['nullable', 'numeric', 'min:0'],
                'variant_logged_in_price' => ['nullable', 'array'],
                'variant_logged_in_price.*' => ['nullable', 'numeric', 'min:0'],
                'variant_retail_price' => ['nullable', 'array'],
                'variant_retail_price.*' => ['nullable', 'numeric', 'min:0'],
                'variant_dealer_price' => ['nullable', 'array'],
                'variant_dealer_price.*' => ['nullable', 'numeric', 'min:0'],
                'variant_institutional_price' => ['nullable', 'array'],
                'variant_institutional_price.*' => ['nullable', 'numeric', 'min:0'],
                'variant_stock_quantity' => ['nullable', 'array'],
                'variant_stock_quantity.*' => ['nullable', 'integer', 'min:0'],
                'variant_is_active' => ['nullable', 'array'],
                'variant_is_active.*' => ['nullable', Rule::in(['0', '1', 0, 1])],
                'variant_attribute_name' => ['nullable', 'array'],
                'variant_attribute_name.*' => ['nullable', 'string', 'max:80'],
                'variant_attribute_value' => ['nullable', 'array'],
                'variant_attribute_value.*' => ['nullable', 'string', 'max:255'],
            ]);

            // Step 2: normalize the publish checkbox into a boolean value.
            $validated['is_published'] = $request->boolean('is_published');

            return $validated;
        } catch (Throwable $exception) {
            Log::error('Failed to validate product CRUD payload.', ['error' => $exception->getMessage()]);
            throw $exception;
        }
    }
}
