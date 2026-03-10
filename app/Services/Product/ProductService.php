<?php

namespace App\Services\Product;

use App\Models\Authorization\User;
use App\Models\Order\OrderItem;
use App\Models\Product\Category;
use App\Models\Product\Product;
use App\Models\Product\ProductImage;
use App\Models\Product\ProductPrice;
use App\Models\Product\ProductVariant;
use App\Models\Product\Subcategory;
use App\Models\Product\UserActivityLog;
use App\Models\Product\VariantAttribute;
use App\Services\Authorization\DataVisibilityService;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Throwable;

class ProductService
{
    public function __construct(
        protected DataVisibilityService $dataVisibilityService,
    ) {
    }

    // This returns the visible product list with price and GST details.
    public function listVisibleProducts(?User $user, ?string $search = null, mixed $categoryFilter = null, mixed $subcategoryFilter = null): LengthAwarePaginator
    {
        try {
            // Step 1: start with the visibility-safe product query.
            $query = $this->dataVisibilityService->visibleProductQuery($user);

            // Step 2: apply search filters when the user searches by text.
            if ($search !== null && trim($search) !== '') {
                $search = trim($search);
                $query->where(function ($builder) use ($search): void {
                    $builder->where('products.name', 'like', '%'.$search.'%')
                        ->orWhere('products.sku', 'like', '%'.$search.'%')
                        ->orWhere('products.description', 'like', '%'.$search.'%')
                        ->orWhere('categories.name', 'like', '%'.$search.'%')
                        ->orWhere('subcategories.name', 'like', '%'.$search.'%');
                });
            }

            // Step 3: apply category filter by id or slug/name.
            if ($categoryFilter !== null && $categoryFilter !== '') {
                if (is_numeric($categoryFilter) && (int) $categoryFilter > 0) {
                    $query->where('products.category_id', (int) $categoryFilter);
                } elseif (is_string($categoryFilter)) {
                    $categoryValue = trim($categoryFilter);

                    if ($categoryValue !== '') {
                        $query->where(function ($builder) use ($categoryValue): void {
                            $builder->where('categories.slug', $categoryValue)
                                ->orWhere('categories.name', $categoryValue);
                        });
                    }
                }
            }

            // Step 4: apply subcategory filter by id or slug/name.
            if ($subcategoryFilter !== null && $subcategoryFilter !== '') {
                if (is_numeric($subcategoryFilter) && (int) $subcategoryFilter > 0) {
                    $query->where('products.subcategory_id', (int) $subcategoryFilter);
                } elseif (is_string($subcategoryFilter)) {
                    $subcategoryValue = trim($subcategoryFilter);

                    if ($subcategoryValue !== '') {
                        $query->where(function ($builder) use ($subcategoryValue): void {
                            $builder->where('subcategories.slug', $subcategoryValue)
                                ->orWhere('subcategories.name', $subcategoryValue);
                        });
                    }
                }
            }

            // Step 5: paginate first, then attach one resolved price per product.
            $products = $query
                ->orderBy('products.name')
                ->paginate(15)
                ->withQueryString();

            $products->setCollection(
                $products->getCollection()->map(function ($product) use ($user) {
                    $price = $this->dataVisibilityService->resolvePrice((int) $product->id, $user);

                    $product->visible_price = $price['amount'] ?? null;
                    $product->gst_rate = $price['gst_rate'] ?? 0;
                    $product->tax_amount = $price['tax_amount'] ?? null;
                    $product->price_with_gst = $price['price_after_gst'] ?? null;
                    $product->visible_currency = $price['currency'] ?? null;
                    $product->visible_price_type = $price['price_type'] ?? null;
                    $product->visible_variant_id = $price['product_variant_id'] ?? null;
                    $product->visible_variant_sku = $price['variant_sku'] ?? null;
                    $product->visible_variant_name = $price['variant_name'] ?? null;

                    return $product;
                }),
            );

            return $products;
        } catch (Throwable $exception) {
            Log::error('Failed to list visible products.', ['user_id' => $user?->id, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This returns one visible product with price and GST details.
    public function findVisibleProduct(?User $user, int $productId): ?object
    {
        try {
            // Step 1: load the requested product from the visibility-safe query.
            $productDetails = $this->dataVisibilityService->visibleProductQuery($user)
                ->where('products.id', $productId)
                ->first();

            // Step 2: attach price and GST details expected by the page.
            if ($productDetails) {
                $price = $this->dataVisibilityService->resolvePrice($productId, $user);

                $productDetails->visible_price = $price['amount'] ?? null;
                $productDetails->gst_rate = $price['gst_rate'] ?? 0;
                $productDetails->tax_amount = $price['tax_amount'] ?? null;
                $productDetails->price_with_gst = $price['price_after_gst'] ?? null;
                $productDetails->visible_currency = $price['currency'] ?? null;
                $productDetails->visible_price_type = $price['price_type'] ?? null;
                $productDetails->visible_variant_id = $price['product_variant_id'] ?? null;
                $productDetails->visible_variant_sku = $price['variant_sku'] ?? null;
                $productDetails->visible_variant_name = $price['variant_name'] ?? null;
            }

            Log::info('ProductService.findVisibleProduct', [ 'productDetails' => $productDetails,'userId' => $user?->id, ]);
            return $productDetails;
        } catch (Throwable $exception) {
            Log::error('Failed to load visible product.', ['product_id' => $productId, 'user_id' => $user?->id, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This forwards price resolution to the visibility service.
    public function resolvePrice(int $productId, ?User $user): ?array
    {
        try {
            return $this->dataVisibilityService->resolvePrice($productId, $user);
        } catch (Throwable $exception) {
            Log::error('Failed to resolve product price from product service.', ['product_id' => $productId, 'user_id' => $user?->id, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This returns product categories for home and other pages.
    public function categories(): Collection
    {
        try {
            return Category::query()
                ->orderBy('name')
                ->get();
        } catch (Throwable $exception) {
            Log::error('Failed to load product categories.', ['error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This returns the top frequently bought together products for one product.
    public function frequentlyBoughtTogetherProducts(int $productId, ?User $user): Collection
    {
        try {
            // Step 1: read the configured top product limit and keep a safe minimum of one.
            $limit = max(1, (int) config('common.frequently_bought_together_limit', 4));

            // Step 2: load the current product because category and subcategory are used for fallback.
            $currentProduct = Product::query()
                ->select(['id', 'category_id', 'subcategory_id'])
                ->find($productId);

            // Step 3: return an empty collection when the current product does not exist.
            if (! $currentProduct) {
                return collect();
            }

            // Step 4: start one ordered list that will hold the final ranked related product ids.
            $selectedProductIds = collect();

            // Step 5: count the top related products directly in the database.
            $topProductFrequencyMap = OrderItem::query()
                ->selectRaw('order_items.product_id, COUNT(*) as frequency_count')
                ->whereIn('order_items.order_id', function ($builder) use ($productId): void {
                    $builder->select('order_items.order_id')
                        ->from('order_items')
                        ->join('orders', 'orders.id', '=', 'order_items.order_id')
                        ->where('order_items.product_id', $productId)
                        ->whereIn('orders.status', ['submitted', 'approved']);
                })
                ->whereNotNull('order_items.product_id')
                ->where('order_items.product_id', '!=', $productId)
                ->groupBy('order_items.product_id')
                ->orderByDesc('frequency_count')
                ->limit($limit)
                ->pluck('frequency_count', 'order_items.product_id');

            // Step 6: keep the ranked frequently-bought ids first in the final ordered list.
            $selectedProductIds = $selectedProductIds->concat(
                $topProductFrequencyMap
                    ->keys()
                    ->map(fn ($relatedProductId) => (int) $relatedProductId)
                    ->values()
            );

            // Step 7: fill the remaining slots from the same subcategory when needed(in case desired no of frequently bought together products is not met).
            if ($selectedProductIds->count() < $limit && $currentProduct->subcategory_id) {
                $remainingCount = $limit - $selectedProductIds->count();

                $sameSubcategoryProductIds = Product::query()
                    ->where('subcategory_id', $currentProduct->subcategory_id)
                    ->where('id', '!=', $productId)
                    ->whereNotIn('id', $selectedProductIds->all())
                    ->where('is_active', true)
                    ->orderBy('name')
                    ->limit($remainingCount)
                    ->pluck('id')
                    ->map(fn ($relatedProductId) => (int) $relatedProductId);

                $selectedProductIds = $selectedProductIds->concat($sameSubcategoryProductIds);
            }

            // Step 8: fill the remaining slots from the same category when needed.
            if ($selectedProductIds->count() < $limit && $currentProduct->category_id) {
                $remainingCount = $limit - $selectedProductIds->count();

                $sameCategoryProductIds = Product::query()
                    ->where('category_id', $currentProduct->category_id)
                    ->where('id', '!=', $productId)
                    ->whereNotIn('id', $selectedProductIds->all())
                    ->where('is_active', true)
                    ->orderBy('name')
                    ->limit($remainingCount)
                    ->pluck('id')
                    ->map(fn ($relatedProductId) => (int) $relatedProductId);

                $selectedProductIds = $selectedProductIds->concat($sameCategoryProductIds);
            }

            // Step 9: keep the final ids unique and stop at the configured limit.
            $topProductIds = $selectedProductIds
                ->unique()
                ->take($limit)
                ->values();

            // Step 10: return an empty collection when no related products remain after fallback.
            if ($topProductIds->isEmpty()) {
                return collect();
            }

            // Step 11: load the related product rows with common relations using Eloquent.
            $products = Product::query()
                ->with([
                    'category:id,name',
                    'subcategory:id,name',
                    'primaryImage:id,file_path',
                ])
                ->whereIn('id', $topProductIds->all())
                ->where('is_active', true)
                ->get();

            // Step 12: key the loaded products by id so ranked lookup stays simple.
            $products = $products->keyBy('id');

            // Step 13: rebuild the final list in ranked order and attach the current visible price fields.
            return $topProductIds
                ->map(function (int $relatedProductId) use ($products, $topProductFrequencyMap, $user) {
                    $product = $products->get($relatedProductId);

                    if (! $product) {
                        return null;
                    }

                    $price = $this->dataVisibilityService->resolvePrice($relatedProductId, $user);

                    if (! $price) {
                        return null;
                    }

                    $product->frequency_count = (int) ($topProductFrequencyMap[$relatedProductId] ?? 0);
                    $product->visible_price = $price['amount'] ?? null;
                    $product->gst_rate = $price['gst_rate'] ?? 0;
                    $product->tax_amount = $price['tax_amount'] ?? null;
                    $product->price_with_gst = $price['price_after_gst'] ?? null;
                    $product->visible_currency = $price['currency'] ?? null;
                    $product->visible_price_type = $price['price_type'] ?? null;
                    $product->visible_variant_id = $price['product_variant_id'] ?? null;
                    $product->visible_variant_sku = $price['variant_sku'] ?? null;
                    $product->visible_variant_name = $price['variant_name'] ?? null;

                    return $product;
                })
                ->filter()
                ->values();
        } catch (Throwable $exception) {
            Log::error('Failed to load frequently bought together products.', ['product_id' => $productId, 'user_id' => $user?->id, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }


    // This stores one activity row for guest and logged-in users in the same table.
    public function logUserActivity(?User $user, string $sessionId, string $path, string $activityType, array $payload = []): void
    {
        try {
            // Step 1: save user details when a user is logged in, otherwise keep guest values.
            UserActivityLog::query()->create([
                'session_id' => $sessionId,
                'user_id' => $user?->id,
                'user_type' => $user?->user_type ?: 'guest',
                'user_name' => $user?->name,
                'user_email' => $user?->email,
                'activity_type' => $activityType,
                'path' => $path,
                'payload' => $payload,
                'created_at' => now(),
            ]);
        } catch (Throwable $exception) {
            Log::error('Failed to log user product activity.', ['session_id' => $sessionId, 'user_id' => $user?->id, 'path' => $path, 'activity_type' => $activityType, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This builds the full product CRUD page data 
    public function productCrudPageData(?int $editProductId = null): array
    {
        try {
            // Step 1: load category and subcategory dropdown data.
            $categories = Category::query()
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get();

            $subcategories = Subcategory::query()
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get();

            // Step 2: load paginated products and expose the old alias fields for the view.
            $products = Product::query()
                ->with([
                    'category:id,name',
                    'subcategory:id,name',
                    'primaryImage:id,file_path',
                ])
                ->select([
                    'id',
                    'name',
                    'slug',
                    'base_sku',
                    'is_published',
                    'created_at',
                    'category_id',
                    'subcategory_id',
                    'product_image_id',
                ])
                ->orderByDesc('id')
                ->paginate(15)
                ->withQueryString();

            $products->setCollection(
                $products->getCollection()->map(function (Product $product) {
                    $product->category_name = $product->category?->name;
                    $product->subcategory_name = $product->subcategory?->name;
                    $product->primary_image_path = $product->primaryImage?->file_path;

                    return $product;
                }),
            );

            // Step 3: default empty edit data when no product is selected.
            $editingProduct = null;
            $editingImages = collect();
            $editingVariants = collect();

            // Step 4: load edit data only when the page is opened in edit mode.
            if ($editProductId !== null) {
                $editingProduct = Product::query()
                    ->select('id', 'name', 'slug', 'description', 'category_id', 'subcategory_id', 'base_sku', 'is_published', 'product_image_id')
                    ->find($editProductId);

                if ($editingProduct) {
                    $editingImages = $editingProduct->images()
                        ->select('id', 'file_path', 'is_primary', 'sort_order')
                        ->orderBy('sort_order')
                        ->orderBy('id')
                        ->get();

                    $editingVariants = $editingProduct->variants()
                        ->with([
                            'attributes' => fn ($query) => $query->orderBy('id'),
                            'prices' => fn ($query) => $query
                                ->whereNull('company_id')
                                ->where('is_active', true)
                                ->orderByDesc('id'),
                        ])
                        ->orderBy('id')
                        ->get()
                        ->map(function (ProductVariant $variant) {
                            $publicPrice = optional($variant->prices->firstWhere('price_type', 'public'))->amount;
                            $loggedInPrice = optional($variant->prices->firstWhere('price_type', 'logged_in'))->amount;
                            $retailPrice = optional($variant->prices->firstWhere('price_type', 'retail'))->amount;
                            $dealerPrice = optional($variant->prices->firstWhere('price_type', 'dealer'))->amount;
                            $institutionalPrice = optional($variant->prices->firstWhere('price_type', 'institutional'))->amount;
                            $firstAttribute = $variant->attributes->first();

                            return (object) [
                                'id' => $variant->id,
                                'sku' => $variant->sku,
                                'variant_name' => $variant->variant_name,
                                'attributes_json' => $variant->attributes_json === null ? null : json_encode($variant->attributes_json),
                                'min_order_quantity' => $variant->min_order_quantity,
                                'max_order_quantity' => $variant->max_order_quantity,
                                'model_number' => $variant->model_number,
                                'catalog_number' => $variant->catalog_number,
                                'public_price' => $publicPrice,
                                'logged_in_price' => $loggedInPrice,
                                'retail_price' => $retailPrice,
                                'dealer_price' => $dealerPrice,
                                'institutional_price' => $institutionalPrice,
                                'price' => $retailPrice,
                                'stock_quantity' => $variant->stock_quantity,
                                'is_active' => $variant->is_active,
                                'attribute_name' => $firstAttribute?->attribute_name,
                                'attribute_value' => $firstAttribute?->attribute_value,
                            ];
                        });
                }
            }

            return [
                'categories' => $categories,
                'subcategories' => $subcategories,
                'products' => $products,
                'editingProduct' => $editingProduct,
                'editingImages' => $editingImages,
                'editingVariants' => $editingVariants,
            ];
        } catch (Throwable $exception) {
            Log::error('Failed to build product CRUD page data.', ['edit_product_id' => $editProductId, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }


    // This creates the product, uploads images, and saves its variants.
    public function createProductCrud(array $validatedProduct, array $images = []): int
    {
        try {
            return DB::transaction(function () use ($validatedProduct, $images): int {
                // Step 1: prepare unique values used by the product row.
                $slug = $this->resolveUniqueSlug((string) ($validatedProduct['slug'] ?? ''), (string) $validatedProduct['name']);
                $legacySku = $this->resolveLegacySku($validatedProduct['base_sku'] ?? null, $slug);

                // Step 2: create the product row.
                $product = Product::query()->create([
                    'name' => $validatedProduct['name'],
                    'slug' => $slug,
                    'description' => $validatedProduct['description'] ?? null,
                    'category_id' => $validatedProduct['category_id'] ?? null,
                    'subcategory_id' => $validatedProduct['subcategory_id'] ?? null,
                    'base_sku' => $validatedProduct['base_sku'] ?? null,
                    'is_published' => (bool) ($validatedProduct['is_published'] ?? false),
                    'product_image_id' => null,
                    'sku' => $legacySku,
                    'visibility_scope' => 'public',
                    'is_active' => (bool) ($validatedProduct['is_published'] ?? false),
                ]);

                // Step 3: save images and set the primary image when images exist.
                $newPrimaryImageId = $this->storeImages($product->id, $images);
                $this->applyPrimaryImage($product->id, $newPrimaryImageId);

                // Step 4: create the default or submitted variants.
                $this->syncProductVariants($product->id, $validatedProduct);

                return $product->id;
            });
        } catch (Throwable $exception) {
            Log::error('Failed to create product.', ['name' => $validatedProduct['name'] ?? null, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This updates the product, image selection, and variant data.
    public function updateProductCrud(int $productId, array $validated, array $newImages = []): void
    {
        try {
            // Step 1: fail fast when the product does not exist.
            $product = Product::query()->find($productId);

            if (! $product) {
                throw ValidationException::withMessages([
                    'product' => 'Product not found.',
                ]);
            }

            DB::transaction(function () use ($product, $validated, $newImages): void {
                // Step 2: update the main product row.
                $slug = $this->resolveUniqueSlug((string) ($validated['slug'] ?? ''), (string) $validated['name'], $product->id);
                $legacySku = $this->resolveLegacySku($validated['base_sku'] ?? null, $slug, $product->id);

                $product->fill([
                    'name' => $validated['name'],
                    'slug' => $slug,
                    'description' => $validated['description'] ?? null,
                    'category_id' => $validated['category_id'] ?? null,
                    'subcategory_id' => $validated['subcategory_id'] ?? null,
                    'base_sku' => $validated['base_sku'] ?? null,
                    'is_published' => (bool) ($validated['is_published'] ?? false),
                    'sku' => $legacySku,
                    'is_active' => (bool) ($validated['is_published'] ?? false),
                ])->save();

                // Step 3: remove deleted images and save new uploads.
                $this->deleteMarkedImages($product->id, $validated['delete_image_ids'] ?? []);
                $newPrimaryImageId = $this->storeImages($product->id, $newImages);

                // Step 4: apply the selected primary image, or the first new one.
                $preferredPrimaryImageId = isset($validated['primary_image_id']) && $validated['primary_image_id'] !== ''
                    ? (int) $validated['primary_image_id']
                    : null;

                if (! $preferredPrimaryImageId && $newPrimaryImageId) {
                    $preferredPrimaryImageId = $newPrimaryImageId;
                }

                $this->applyPrimaryImage($product->id, $preferredPrimaryImageId);

                // Step 5: create, update, or delete variants from the submitted rows.
                $this->syncProductVariants($product->id, $validated);
            });
        } catch (Throwable $exception) {
            Log::error('Failed to update product.', ['product_id' => $productId, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This deletes a product with its images, prices, and variants.
    public function deleteProductCrud(int $productId): void
    {
        try {
            $product = Product::query()
                ->with(['images:id,product_id,file_path', 'variants:id,product_id'])
                ->find($productId);

            if (! $product) {
                throw ValidationException::withMessages([
                    'product' => 'Product not found.',
                ]);
            }

            DB::transaction(function () use ($product): void {
                // Step 1: delete all variants first so their dependent rows are removed cleanly.
                foreach ($product->variants as $variant) {
                    $this->deleteProductVariant($variant->id);
                }

                // Step 2: delete all uploaded image files and image rows.
                foreach ($product->images as $image) {
                    if ($image->file_path) {
                        Storage::disk('public')->delete($image->file_path);
                    }
                }

                $product->images()->delete();

                // Step 3: delete the main product row.
                $product->delete();
            });
        } catch (Throwable $exception) {
            Log::error('Failed to delete product.', ['product_id' => $productId, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This generates a unique slug for the product.
    protected function resolveUniqueSlug(string $slugInput, string $name, ?int $ignoreProductId = null): string
    {
        try {
            $seedValue = trim($slugInput) !== '' ? $slugInput : $name;
            $baseSlug = Str::slug($seedValue);
            $baseSlug = $baseSlug !== '' ? $baseSlug : 'product';
            $slug = $baseSlug;
            $counter = 1;

            while (Product::query()
                ->where('slug', $slug)
                ->when($ignoreProductId, fn ($query) => $query->where('id', '!=', $ignoreProductId))
                ->exists()) {
                $slug = $baseSlug.'-'.$counter;
                $counter++;
            }

            return $slug;
        } catch (Throwable $exception) {
            Log::error('Failed to resolve product slug.', ['name' => $name, 'ignore_product_id' => $ignoreProductId, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This generates a unique legacy SKU for the product row.
    protected function resolveLegacySku(mixed $baseSku, string $fallbackSlug, ?int $ignoreProductId = null): string
    {
        try {
            $baseValue = trim((string) ($baseSku ?? ''));
            $seedValue = $baseValue !== '' ? $baseValue : $fallbackSlug;
            $seedValue = Str::of($seedValue)->replace('-', '_')->upper()->toString();
            $seedValue = preg_replace('/[^A-Z0-9_]/', '', $seedValue) ?: 'PRODUCT';
            $sku = $seedValue;
            $counter = 1;

            while (Product::query()
                ->where('sku', $sku)
                ->when($ignoreProductId, fn ($query) => $query->where('id', '!=', $ignoreProductId))
                ->exists()) {
                $sku = $seedValue.'_'.$counter;
                $counter++;
            }

            return $sku;
        } catch (Throwable $exception) {
            Log::error('Failed to resolve legacy product SKU.', ['base_sku' => $baseSku, 'ignore_product_id' => $ignoreProductId, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This stores uploaded images and returns the first new image id.
    protected function storeImages(int $productId, array $images): ?int
    {
        try {
            // Step 1: continue image sorting after the last saved image.
            $sortOrder = ((int) ProductImage::query()
                ->where('product_id', $productId)
                ->max('sort_order')) + 1;

            $newPrimaryImageId = null;

            // Step 2: save each uploaded image row and file path.
            foreach ($images as $image) {
                if (! $image instanceof UploadedFile) {
                    continue;
                }

                $filePath = $image->store('products/'.$productId, 'public');

                $savedImage = ProductImage::query()->create([
                    'product_id' => $productId,
                    'file_path' => $filePath,
                    'is_primary' => false,
                    'sort_order' => $sortOrder,
                ]);

                if ($newPrimaryImageId === null) {
                    $newPrimaryImageId = $savedImage->id;
                }

                $sortOrder++;
            }

            return $newPrimaryImageId;
        } catch (Throwable $exception) {
            Log::error('Failed to store product images.', ['product_id' => $productId, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This deletes the selected images from storage and the database.
    protected function deleteMarkedImages(int $productId, mixed $imageIds): void
    {
        try {
            // Step 1: keep only valid numeric image ids.
            $ids = collect(is_array($imageIds) ? $imageIds : [])
                ->map(fn ($id) => (int) $id)
                ->filter(fn ($id) => $id > 0)
                ->values();

            if ($ids->isEmpty()) {
                return;
            }

            // Step 2: load the image rows before deleting the files.
            $images = ProductImage::query()
                ->where('product_id', $productId)
                ->whereIn('id', $ids->all())
                ->get(['id', 'file_path']);

            foreach ($images as $image) {
                if ($image->file_path) {
                    Storage::disk('public')->delete($image->file_path);
                }
            }

            // Step 3: delete the database rows after files are removed.
            ProductImage::query()
                ->where('product_id', $productId)
                ->whereIn('id', $ids->all())
                ->delete();
        } catch (Throwable $exception) {
            Log::error('Failed to delete marked product images.', ['product_id' => $productId, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This marks one image as primary and clears the old primary image.
    protected function applyPrimaryImage(int $productId, ?int $preferredPrimaryImageId = null): void
    {
        try {
            // Step 1: use the selected image if it belongs to the same product.
            $primaryImageId = null;

            if ($preferredPrimaryImageId) {
                $primaryImageId = ProductImage::query()
                    ->where('product_id', $productId)
                    ->where('id', $preferredPrimaryImageId)
                    ->value('id');
            }

            // Step 2: when nothing is selected, use the first image as fallback.
            if (! $primaryImageId) {
                $primaryImageId = ProductImage::query()
                    ->where('product_id', $productId)
                    ->orderBy('sort_order')
                    ->orderBy('id')
                    ->value('id');
            }

            // Step 3: reset all image rows first so only one row stays primary.
            ProductImage::query()
                ->where('product_id', $productId)
                ->update([
                    'is_primary' => false,
                    'updated_at' => now(),
                ]);

            if ($primaryImageId) {
                ProductImage::query()
                    ->where('product_id', $productId)
                    ->where('id', $primaryImageId)
                    ->update([
                        'is_primary' => true,
                        'updated_at' => now(),
                    ]);
            }

            // Step 4: save the selected primary image id on the product row.
            Product::query()
                ->where('id', $productId)
                ->update([
                    'product_image_id' => $primaryImageId ?: null,
                    'updated_at' => now(),
                ]);
        } catch (Throwable $exception) {
            Log::error('Failed to apply primary image.', ['product_id' => $productId, 'preferred_primary_image_id' => $preferredPrimaryImageId, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This creates, updates, or deletes submitted variant rows for a product.
    protected function syncProductVariants(int $productId, array $validated): void
    {
        try {
            // Step 1: load the current default variant id so it cannot be deleted by mistake.
            $defaultVariantId = ProductVariant::query()
                ->where('product_id', $productId)
                ->orderBy('id')
                ->value('id');

            // Step 2: read all variant row arrays from the request payload.
            $variantIdRows = is_array($validated['variant_id'] ?? null) ? $validated['variant_id'] : [];
            $variantDeleteRows = is_array($validated['variant_delete'] ?? null) ? $validated['variant_delete'] : [];
            $skuRows = is_array($validated['variant_sku'] ?? null) ? $validated['variant_sku'] : [];
            $variantNameRows = is_array($validated['variant_name'] ?? null) ? $validated['variant_name'] : [];
            $variantAttributesJsonRows = is_array($validated['variant_attributes_json'] ?? null) ? $validated['variant_attributes_json'] : [];
            $minOrderRows = is_array($validated['variant_min_order_quantity'] ?? null) ? $validated['variant_min_order_quantity'] : [];
            $maxOrderRows = is_array($validated['variant_max_order_quantity'] ?? null) ? $validated['variant_max_order_quantity'] : [];
            $modelNumberRows = is_array($validated['variant_model_number'] ?? null) ? $validated['variant_model_number'] : [];
            $catalogNumberRows = is_array($validated['variant_catalog_number'] ?? null) ? $validated['variant_catalog_number'] : [];
            $legacyPriceRows = is_array($validated['variant_price'] ?? null) ? $validated['variant_price'] : [];
            $publicPriceRows = is_array($validated['variant_public_price'] ?? null) ? $validated['variant_public_price'] : [];
            $loggedInPriceRows = is_array($validated['variant_logged_in_price'] ?? null) ? $validated['variant_logged_in_price'] : [];
            $retailPriceRows = is_array($validated['variant_retail_price'] ?? null) ? $validated['variant_retail_price'] : [];
            $dealerPriceRows = is_array($validated['variant_dealer_price'] ?? null) ? $validated['variant_dealer_price'] : [];
            $institutionalPriceRows = is_array($validated['variant_institutional_price'] ?? null) ? $validated['variant_institutional_price'] : [];
            $stockRows = is_array($validated['variant_stock_quantity'] ?? null) ? $validated['variant_stock_quantity'] : [];
            $activeRows = is_array($validated['variant_is_active'] ?? null) ? $validated['variant_is_active'] : [];
            $attributeNameRows = is_array($validated['variant_attribute_name'] ?? null) ? $validated['variant_attribute_name'] : [];
            $attributeValueRows = is_array($validated['variant_attribute_value'] ?? null) ? $validated['variant_attribute_value'] : [];

            // Step 3: find the maximum row count so every submitted row is checked.
            $rowCount = max(
                count($variantIdRows),
                count($variantDeleteRows),
                count($skuRows),
                count($variantNameRows),
                count($variantAttributesJsonRows),
                count($minOrderRows),
                count($maxOrderRows),
                count($modelNumberRows),
                count($catalogNumberRows),
                count($legacyPriceRows),
                count($publicPriceRows),
                count($loggedInPriceRows),
                count($retailPriceRows),
                count($dealerPriceRows),
                count($institutionalPriceRows),
                count($stockRows),
                count($activeRows),
                count($attributeNameRows),
                count($attributeValueRows),
            );

            $seenSkus = [];

            // Step 4: create, update, or delete each submitted variant row.
            for ($index = 0; $index < $rowCount; $index++) {
                $variantId = (int) ($variantIdRows[$index] ?? 0);
                $deleteFlag = (string) ($variantDeleteRows[$index] ?? '0') === '1';
                $sku = trim((string) ($skuRows[$index] ?? ''));
                $variantName = trim((string) ($variantNameRows[$index] ?? ''));
                $variantAttributesJsonRaw = trim((string) ($variantAttributesJsonRows[$index] ?? ''));
                $minOrderRaw = $minOrderRows[$index] ?? null;
                $maxOrderRaw = $maxOrderRows[$index] ?? null;
                $modelNumber = trim((string) ($modelNumberRows[$index] ?? ''));
                $catalogNumber = trim((string) ($catalogNumberRows[$index] ?? ''));
                $legacyPriceRaw = $legacyPriceRows[$index] ?? null;
                $publicPriceRaw = $publicPriceRows[$index] ?? null;
                $loggedInPriceRaw = $loggedInPriceRows[$index] ?? null;
                $retailPriceRaw = $retailPriceRows[$index] ?? null;
                $dealerPriceRaw = $dealerPriceRows[$index] ?? null;
                $institutionalPriceRaw = $institutionalPriceRows[$index] ?? null;
                $stockRaw = $stockRows[$index] ?? null;
                $activeRaw = $activeRows[$index] ?? null;
                $attributeName = trim((string) ($attributeNameRows[$index] ?? ''));
                $attributeValue = trim((string) ($attributeValueRows[$index] ?? ''));

                // Step 4a: ignore the empty optional variant row.
                $hasRealVariantInput = $sku !== ''
                    || $variantName !== ''
                    || $variantAttributesJsonRaw !== ''
                    || (($minOrderRaw !== null && $minOrderRaw !== '') && (int) $minOrderRaw !== 1)
                    || ($maxOrderRaw !== null && $maxOrderRaw !== '')
                    || $modelNumber !== ''
                    || $catalogNumber !== ''
                    || ($legacyPriceRaw !== null && $legacyPriceRaw !== '')
                    || ($publicPriceRaw !== null && $publicPriceRaw !== '')
                    || ($loggedInPriceRaw !== null && $loggedInPriceRaw !== '')
                    || ($retailPriceRaw !== null && $retailPriceRaw !== '')
                    || ($dealerPriceRaw !== null && $dealerPriceRaw !== '')
                    || ($institutionalPriceRaw !== null && $institutionalPriceRaw !== '')
                    || ($stockRaw !== null && $stockRaw !== '')
                    || $attributeName !== ''
                    || $attributeValue !== '';

                $isEmptyRow = $variantId === 0 && ! $deleteFlag && ! $hasRealVariantInput;

                if ($isEmptyRow) {
                    continue;
                }

                // Step 4b: allow explicit delete only for non-default variants.
                if ($deleteFlag && $variantId > 0 && $variantId !== (int) $defaultVariantId) {
                    $this->deleteProductVariant($variantId);
                    continue;
                }

                // Step 4c: keep the existing SKU when edit rows leave SKU empty.
                if ($sku === '' && $variantId > 0) {
                    $existingSku = ProductVariant::query()
                        ->where('id', $variantId)
                        ->value('sku');

                    $sku = $existingSku ? (string) $existingSku : '';
                }

                // Step 4d: auto-generate a SKU when no SKU is supplied.
                if ($sku === '') {
                    $sku = 'PV-'.$productId.'-'.($index + 1).'-'.time();
                }

                // Step 4e: keep SKUs unique inside the same save request.
                if (isset($seenSkus[$sku])) {
                    throw ValidationException::withMessages([
                        'variant_sku' => "Duplicate SKU '{$sku}' in submitted variants.",
                    ]);
                }

                $seenSkus[$sku] = true;

                // Step 4f: normalize quantity and active values.
                $minOrderQuantity = (is_numeric($minOrderRaw) && (int) $minOrderRaw > 0) ? (int) $minOrderRaw : 1;
                $maxOrderQuantity = (is_numeric($maxOrderRaw) && (int) $maxOrderRaw > 0) ? (int) $maxOrderRaw : null;
                $stockQuantity = $stockRaw === null || $stockRaw === '' ? 0 : (int) $stockRaw;
                $isVariantActive = (string) $activeRaw !== '0';

                // Step 4g: normalize the JSON attributes payload.
                $attributesJson = null;
                if ($variantAttributesJsonRaw !== '') {
                    $decodedAttributes = json_decode($variantAttributesJsonRaw, true);
                    $attributesJson = is_array($decodedAttributes) ? $decodedAttributes : ['value' => $variantAttributesJsonRaw];
                } elseif ($attributeName !== '' || $attributeValue !== '') {
                    $attributesJson = [
                        ($attributeName !== '' ? $attributeName : 'attribute') => $attributeValue,
                    ];
                }

                // Step 4h: collect all supported generic prices for the variant.
                $retailValue = $retailPriceRaw;

                if ($retailValue === null || $retailValue === '') {
                    $retailValue = $legacyPriceRaw;
                }

                $priceMap = [
                    'public' => $publicPriceRaw,
                    'logged_in' => $loggedInPriceRaw,
                    'retail' => $retailValue,
                    'dealer' => $dealerPriceRaw,
                    'institutional' => $institutionalPriceRaw,
                ];

                $hasAnyPrice = false;
                foreach ($priceMap as $priceValue) {
                    if ($priceValue !== null && $priceValue !== '') {
                        $hasAnyPrice = true;
                        break;
                    }
                }

                if (! $hasAnyPrice) {
                    $priceMap['retail'] = 0;
                }

                $variantData = [
                    'sku' => $sku,
                    'variant_name' => $variantName !== '' ? $variantName : 'Default Variant',
                    'attributes_json' => $attributesJson,
                    'min_order_quantity' => $minOrderQuantity,
                    'max_order_quantity' => $maxOrderQuantity,
                    'model_number' => $modelNumber !== '' ? $modelNumber : null,
                    'catalog_number' => $catalogNumber !== '' ? $catalogNumber : null,
                    'stock_quantity' => $stockQuantity,
                    'is_active' => $isVariantActive,
                    'attribute_name' => $attributeName,
                    'attribute_value' => $attributeValue,
                    'prices' => $priceMap,
                ];

                // Step 4i: update existing variants or create new ones.
                if ($variantId > 0) {
                    $this->updateProductVariant($productId, $variantId, $variantData);
                } else {
                    $this->createProductVariant($productId, $variantData);
                }
            }

            // Step 5: keep one default variant when the product has no variants left.
            $hasAnyVariant = ProductVariant::query()
                ->where('product_id', $productId)
                ->exists();

            if (! $hasAnyVariant) {
                $this->createProductVariant($productId, [
                    'sku' => 'PV-'.$productId.'-DEFAULT',
                    'variant_name' => 'Default Variant',
                    'attributes_json' => null,
                    'min_order_quantity' => 1,
                    'max_order_quantity' => null,
                    'model_number' => null,
                    'catalog_number' => null,
                    'stock_quantity' => 0,
                    'is_active' => true,
                    'attribute_name' => '',
                    'attribute_value' => '',
                    'prices' => ['retail' => 0],
                ]);
            }
        } catch (Throwable $exception) {
            Log::error('Failed to sync product variants.', ['product_id' => $productId, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    /**
     * @param  array<string, mixed>  $variantData
     */
    // This creates one product variant with its price rows and legacy attribute row.
    protected function createProductVariant(int $productId, array $variantData): int
    {
        try {
            // Step 1: validate the SKU before inserting the variant.
            $sku = (string) ($variantData['sku'] ?? '');

            if (ProductVariant::query()->where('sku', $sku)->exists()) {
                throw ValidationException::withMessages([
                    'variant_sku' => "SKU '{$sku}' already exists.",
                ]);
            }

            // Step 2: create the variant row.
            $variant = ProductVariant::query()->create([
                'product_id' => $productId,
                'sku' => $sku,
                'variant_name' => (string) ($variantData['variant_name'] ?? 'Default Variant'),
                'attributes_json' => $variantData['attributes_json'] ?? null,
                'min_order_quantity' => (int) ($variantData['min_order_quantity'] ?? 1),
                'max_order_quantity' => $variantData['max_order_quantity'] ?? null,
                'model_number' => $variantData['model_number'] ?? null,
                'catalog_number' => $variantData['catalog_number'] ?? null,
                'stock_quantity' => (int) ($variantData['stock_quantity'] ?? 0),
                'is_active' => (bool) ($variantData['is_active'] ?? true),
            ]);

            // Step 3: save all generic price rows for the variant.
            $this->saveVariantPrices($variant->id, $variantData);

            // Step 4: keep the legacy attribute table in sync for current UI usage.
            $attributeName = trim((string) ($variantData['attribute_name'] ?? ''));
            $attributeValue = trim((string) ($variantData['attribute_value'] ?? ''));

            if ($attributeName !== '' || $attributeValue !== '') {
                VariantAttribute::query()->create([
                    'product_variant_id' => $variant->id,
                    'attribute_name' => $attributeName !== '' ? $attributeName : 'attribute',
                    'attribute_value' => $attributeValue,
                ]);
            }

            return $variant->id;
        } catch (Throwable $exception) {
            Log::error('Failed to create product variant.', ['product_id' => $productId, 'sku' => $variantData['sku'] ?? null, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This updates one variant and replaces its generic prices and attributes.
    protected function updateProductVariant(int $productId, int $variantId, array $variantData): void
    {
        try {
            // Step 1: ignore invalid variant ids that do not belong to the product.
            $variant = ProductVariant::query()
                ->where('id', $variantId)
                ->where('product_id', $productId)
                ->first();

            if (! $variant) {
                return;
            }

            // Step 2: validate that the SKU stays unique.
            $sku = (string) ($variantData['sku'] ?? '');
            $skuExists = ProductVariant::query()
                ->where('sku', $sku)
                ->where('id', '!=', $variantId)
                ->exists();

            if ($skuExists) {
                throw ValidationException::withMessages([
                    'variant_sku' => "SKU '{$sku}' already exists.",
                ]);
            }

            // Step 3: update the variant row.
            $variant->fill([
                'sku' => $sku,
                'variant_name' => (string) ($variantData['variant_name'] ?? 'Default Variant'),
                'attributes_json' => $variantData['attributes_json'] ?? null,
                'min_order_quantity' => (int) ($variantData['min_order_quantity'] ?? 1),
                'max_order_quantity' => $variantData['max_order_quantity'] ?? null,
                'model_number' => $variantData['model_number'] ?? null,
                'catalog_number' => $variantData['catalog_number'] ?? null,
                'stock_quantity' => (int) ($variantData['stock_quantity'] ?? 0),
                'is_active' => (bool) ($variantData['is_active'] ?? true),
            ])->save();

            // Step 4: replace generic prices with the latest submitted values.
            $this->saveVariantPrices($variant->id, $variantData);

            // Step 5: replace the legacy attribute row used by the current CRUD UI.
            $variant->attributes()->delete();

            $attributeName = trim((string) ($variantData['attribute_name'] ?? ''));
            $attributeValue = trim((string) ($variantData['attribute_value'] ?? ''));

            if ($attributeName !== '' || $attributeValue !== '') {
                VariantAttribute::query()->create([
                    'product_variant_id' => $variant->id,
                    'attribute_name' => $attributeName !== '' ? $attributeName : 'attribute',
                    'attribute_value' => $attributeValue,
                ]);
            }
        } catch (Throwable $exception) {
            Log::error('Failed to update product variant.', ['product_id' => $productId, 'variant_id' => $variantId, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This deletes one variant with its price rows and attribute rows.
    protected function deleteProductVariant(int $variantId): void
    {
        try {
            $variant = ProductVariant::query()->find($variantId);

            if (! $variant) {
                return;
            }

            // Step 1: delete prices linked to the variant.
            $variant->prices()->delete();

            // Step 2: delete legacy attribute rows linked to the variant.
            $variant->attributes()->delete();

            // Step 3: delete the variant row itself.
            $variant->delete();
        } catch (Throwable $exception) {
            Log::error('Failed to delete product variant.', ['variant_id' => $variantId, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This replaces generic price rows and stores GST plus quantity rules on each row.
    protected function saveVariantPrices(int $variantId, array $variantData): void
    {
        try {
            // Step 1: remove old generic prices but keep any company-specific prices.
            ProductPrice::query()
                ->where('product_variant_id', $variantId)
                ->whereNull('company_id')
                ->delete();

            // Step 2: load the effective GST rate once for this variant.
            $variant = ProductVariant::query()
                ->with(['product.category'])
                ->find($variantId);

            $gstRate = 0.0;
            if ($variant?->product?->gst_rate !== null) {
                $gstRate = (float) $variant->product->gst_rate;
            } elseif ($variant?->product?->category?->gst_rate !== null) {
                $gstRate = (float) $variant->product->category->gst_rate;
            }

            // Step 3: insert each submitted generic price type.
            $prices = is_array($variantData['prices'] ?? null) ? $variantData['prices'] : [];
            $minOrderQuantity = max(1, (int) ($variantData['min_order_quantity'] ?? 1));
            $maxOrderValue = $variantData['max_order_quantity'] ?? null;
            $maxOrderQuantity = $maxOrderValue === null || $maxOrderValue === ''
                ? null
                : max($minOrderQuantity, (int) $maxOrderValue);
            $lotSize = max(1, (int) ($variantData['lot_size'] ?? 1));
            $quantity = max(
                (int) ($variantData['stock_quantity'] ?? 0),
                $minOrderQuantity,
            );
            $isActive = (bool) ($variantData['is_active'] ?? true);

            foreach (['public', 'logged_in', 'retail', 'dealer', 'institutional'] as $priceType) {
                $priceValue = $prices[$priceType] ?? null;

                if ($priceValue === null || $priceValue === '') {
                    continue;
                }

                $amount = (float) $priceValue;
                $taxAmount = round(($amount * $gstRate) / 100, 2);

                ProductPrice::query()->create([
                    'product_variant_id' => $variantId,
                    'price_type' => $priceType,
                    'company_id' => null,
                    'amount' => $amount,
                    'gst_rate' => $gstRate,
                    'tax_amount' => $taxAmount,
                    'price_after_gst' => round($amount + $taxAmount, 2),
                    'currency' => 'INR',
                    'min_order_quantity' => $minOrderQuantity,
                    'max_order_quantity' => $maxOrderQuantity,
                    'lot_size' => $lotSize,
                    'quantity' => $quantity,
                    'is_active' => $isActive,
                ]);
            }

            // Step 4: ensure the variant always has at least one generic visible price.
            $hasPrice = ProductPrice::query()
                ->where('product_variant_id', $variantId)
                ->whereNull('company_id')
                ->exists();

            if (! $hasPrice) {
                $fallbackAmount = 0.0;
                $fallbackTaxAmount = round(($fallbackAmount * $gstRate) / 100, 2);

                ProductPrice::query()->create([
                    'product_variant_id' => $variantId,
                    'price_type' => 'retail',
                    'company_id' => null,
                    'amount' => $fallbackAmount,
                    'gst_rate' => $gstRate,
                    'tax_amount' => $fallbackTaxAmount,
                    'price_after_gst' => round($fallbackAmount + $fallbackTaxAmount, 2),
                    'currency' => 'INR',
                    'min_order_quantity' => $minOrderQuantity,
                    'max_order_quantity' => $maxOrderQuantity,
                    'lot_size' => $lotSize,
                    'quantity' => $quantity > 0 ? $quantity : 1,
                    'is_active' => $isActive,
                ]);
            }
        } catch (Throwable $exception) {
            Log::error('Failed to save variant prices.', ['variant_id' => $variantId, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }
}
