<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function index(Request $request, ProductService $productService): View
    {
        $search = null;
        foreach (['search_text', 'search_value', 'search'] as $searchKey) {
            if ($request->filled($searchKey)) {
                $search = trim((string) $request->input($searchKey));
                break;
            }
        }
        Log::info('Product search initiated', ['search' => $search]);

        $categoryFilter = $request->input('category_id', $request->input('category'));
        $subcategoryFilter = $request->input('subcategory_id', $request->input('subcategory'));

        Log::info('Product filters', ['category_id' => $categoryFilter, 'subcategory_id' => $subcategoryFilter ]);

        if (is_string($categoryFilter)) {
            $categoryFilter = trim($categoryFilter);
            $categoryFilter = $categoryFilter === '' ? null : $categoryFilter;
        }

        if (is_string($subcategoryFilter)) {
            $subcategoryFilter = trim($subcategoryFilter);
            $subcategoryFilter = $subcategoryFilter === '' ? null : $subcategoryFilter;
        }

        $user = $request->user();
        $products = $productService->listVisibleProducts($user, $search, $categoryFilter, $subcategoryFilter);

        if (! $user) {
            $productService->logGuestActivity($request->session()->getId(), $request->path(), 'product_browse', [
                'search' => $search,
                'category' => $categoryFilter,
                'subcategory' => $subcategoryFilter,
            ]);
        }

        Log::info('productController.index Product search results Is:', [$products]);
        return view('prelogin.products', [
            'products' => $products
        ]);
    }

    public function productDetails(int $productId, Request $request, ProductService $productService): View
    {
        $user = $request->user();
        $product = $productService->findVisibleProduct($user, $productId);

        abort_if(! $product, 404);


        if (! $user) {
            $productService->logGuestActivity($request->session()->getId(), $request->path(), 'product_view', [
                'product_id' => $productId,
            ]);
        }

         Log::info('productController.productDetails Product details:', [$product]);
        return view('prelogin.product-details', [
            'id' => $productId,
            'product' => $product
        ]);
    }

    public function index1(Request $request, ProductService $productService): View
    {
        $editProductId = $request->filled('edit_product_id')
            ? (int) $request->input('edit_product_id')
            : null;

        return view('product.ProductCrud', $productService->productCrudPageData($editProductId));
    }

    public function show1(int $productId, ProductService $productService): View
    {
        return view('product.ProductCrud', $productService->productCrudPageData($productId));
    }

    public function store1(Request $request, ProductService $productService): RedirectResponse
    {
        $validated = $this->validateCrudPayload($request);
        $productId = $productService->createProductCrud($validated, $request->file('images', []));

        return redirect('/products-crud?edit_product_id='.$productId)
            ->with('status', 'Product created successfully.');
    }

    public function update1(int $productId, Request $request, ProductService $productService): RedirectResponse
    {
        $validated = $this->validateCrudPayload($request);
        $productService->updateProductCrud($productId, $validated, $request->file('images', []));

        return redirect('/products-crud?edit_product_id='.$productId)
            ->with('status', 'Product updated successfully.');
    }

    public function destroy1(int $productId, ProductService $productService): RedirectResponse
    {
        $productService->deleteProductCrud($productId);

        return redirect('/products-crud')
            ->with('status', 'Product deleted successfully.');
    }

    /**
     * @return array<string, mixed>
     */
    protected function validateCrudPayload(Request $request): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'subcategory_id' => ['nullable', 'integer', 'exists:subcategories,id'],
            'base_sku' => ['nullable', 'string', 'max:120'],
            'is_published' => ['nullable', 'boolean'],

            'images' => ['nullable', 'array', 'max:10'],
            'images.*' => ['file', 'image', 'max:8192'],
            'delete_image_ids' => ['nullable', 'array'],
            'delete_image_ids.*' => ['integer'],
            'primary_image_id' => ['nullable', 'integer'],

            'variant_sku' => ['nullable', 'array'],
            'variant_sku.*' => ['nullable', 'string', 'max:120'],
            'variant_price' => ['nullable', 'array'],
            'variant_price.*' => ['nullable', 'numeric', 'min:0'],
            'variant_stock_quantity' => ['nullable', 'array'],
            'variant_stock_quantity.*' => ['nullable', 'integer', 'min:0'],
            'variant_is_active' => ['nullable', 'array'],
            'variant_is_active.*' => ['nullable', Rule::in(['0', '1', 0, 1])],
            'variant_attribute_name' => ['nullable', 'array'],
            'variant_attribute_name.*' => ['nullable', 'string', 'max:80'],
            'variant_attribute_value' => ['nullable', 'array'],
            'variant_attribute_value.*' => ['nullable', 'string', 'max:255'],
        ]);

        $validated['is_published'] = $request->boolean('is_published');

        return $validated;
    }
}
