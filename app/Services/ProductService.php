<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;


class ProductService
{
    public function __construct(
        protected DataVisibilityService $dataVisibilityService,
    ) {
    }

    public function listVisibleProducts(?User $user, ?string $search = null, mixed $categoryFilter = null, mixed $subcategoryFilter = null): LengthAwarePaginator
    {
        $query = $this->dataVisibilityService->visibleProductQuery($user);

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

        if ($categoryFilter !== null && $categoryFilter !== '') {
            if (is_numeric($categoryFilter) && (int) $categoryFilter > 0) {
                $query->where('products.category_id', (int) $categoryFilter);
            } elseif (is_string($categoryFilter)) {
                $value = trim($categoryFilter);
                if ($value !== '') {
                    $query->where(function ($builder) use ($value): void {
                        $builder->where('categories.slug', $value)
                            ->orWhere('categories.name', $value);
                    });
                }
            }
        }

        if ($subcategoryFilter !== null && $subcategoryFilter !== '') {
            if (is_numeric($subcategoryFilter) && (int) $subcategoryFilter > 0) {
                $query->where('products.subcategory_id', (int) $subcategoryFilter);
            } elseif (is_string($subcategoryFilter)) {
                $value = trim($subcategoryFilter);
                if ($value !== '') {
                    $query->where(function ($builder) use ($value): void {
                        $builder->where('subcategories.slug', $value)
                            ->orWhere('subcategories.name', $value);
                    });
                }
            }
        }

        $products = $query
            ->orderBy('products.name')
            ->paginate(15)
            ->withQueryString();

        // $products->setCollection(
        //     $products->getCollection()->map(function ($product) use ($user) {
        //         $price = $this->dataVisibilityService->resolvePrice((int) $product->id, $user);
        //         $product->visible_price = $price['amount'] ?? null;
        //         $product->visible_currency = $price['currency'] ?? null;
        //         $product->visible_price_type = $price['price_type'] ?? null;

        //         return $product;
        //     }),
        // );

        return $products;
    }

    public function findVisibleProduct(?User $user, int $productId): ?object
    {
        $productDetails = $this->dataVisibilityService->visibleProductQuery($user)
            ->where('products.id', $productId)
            ->first();

        Log::info('ProductService.findVisibleProduct', [
            'productDetails' => $productDetails,
            'userId' => $user ? $user->id : null,
        ]);

        return $productDetails;
    }

   
    public function resolvePrice(int $productId, ?User $user): ?array
    {
        return $this->dataVisibilityService->resolvePrice($productId, $user);
    }

    public function categories(): Collection
    {
        return DB::table('categories')->orderBy('name')->get();
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function logGuestActivity(string $sessionId, string $path, string $activityType, array $payload = []): void
    {
        DB::table('guest_activity_logs')->insert([
            'session_id' => $sessionId,
            'activity_type' => $activityType,
            'path' => $path,
            'payload' => json_encode($payload),
            'created_at' => now(),
        ]);
    }

    /**
     * @return array{
     *     categories: Collection,
     *     subcategories: Collection,
     *     products: LengthAwarePaginator,
     *     editingProduct: object|null,
     *     editingImages: Collection,
     *     editingVariants: Collection
     * }
     */
    public function productCrudPageData(?int $editProductId = null): array
    {
        $categories = DB::table('categories')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $subcategories = DB::table('subcategories')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $products = DB::table('products as p')
            ->leftJoin('categories as c', 'c.id', '=', 'p.category_id')
            ->leftJoin('subcategories as s', 's.id', '=', 'p.subcategory_id')
            ->leftJoin('product_image as pi', 'pi.id', '=', 'p.product_image_id')
            ->select(
                'p.id',
                'p.name',
                'p.slug',
                'p.base_sku',
                'p.is_published',
                'p.created_at',
                'c.name as category_name',
                's.name as subcategory_name',
                'pi.file_path as primary_image_path',
            )
            ->orderByDesc('p.id')
            ->paginate(15)
            ->withQueryString();

        $editingProduct = null;
        $editingImages = collect();
        $editingVariants = collect();

        if ($editProductId !== null) {
            $editingProduct = DB::table('products')
                ->select('id', 'name', 'slug', 'description', 'category_id', 'subcategory_id', 'base_sku', 'is_published', 'product_image_id')
                ->where('id', $editProductId)
                ->first();

            if ($editingProduct) {
                $editingImages = DB::table('product_image')
                    ->select('id', 'file_path', 'is_primary', 'sort_order')
                    ->where('product_id', $editProductId)
                    ->orderBy('sort_order')
                    ->orderBy('id')
                    ->get();

                $editingVariants = DB::table('product_variants as pv')
                    ->leftJoin('variant_attributes as va', 'va.product_variant_id', '=', 'pv.id')
                    ->select(
                        'pv.id',
                        'pv.sku',
                        'pv.price',
                        'pv.stock_quantity',
                        'pv.is_active',
                        'va.attribute_name',
                        'va.attribute_value',
                    )
                    ->where('pv.product_id', $editProductId)
                    ->orderBy('pv.id')
                    ->get();
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
    }

    /**
     * @param  array<string, mixed>  $validated
     * @param  array<int, UploadedFile>  $images
     */
    public function createProductCrud(array $validated, array $images = []): int
    {
        return DB::transaction(function () use ($validated, $images): int {
            $slug = $this->resolveUniqueSlug((string) ($validated['slug'] ?? ''), (string) $validated['name']);
            $legacySku = $this->resolveLegacySku($validated['base_sku'] ?? null, $slug);
            $now = now();

            $insertPayload = [
                'name' => $validated['name'],
                'slug' => $slug,
                'description' => $validated['description'] ?? null,
                'category_id' => $validated['category_id'] ?? null,
                'subcategory_id' => $validated['subcategory_id'] ?? null,
                'base_sku' => $validated['base_sku'] ?? null,
                'is_published' => (bool) ($validated['is_published'] ?? false),
                'product_image_id' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ];

            if (Schema::hasColumn('products', 'sku')) {
                $insertPayload['sku'] = $legacySku;
            }

            if (Schema::hasColumn('products', 'is_active')) {
                $insertPayload['is_active'] = (bool) ($validated['is_published'] ?? false);
            }

            if (Schema::hasColumn('products', 'visibility_scope')) {
                $insertPayload['visibility_scope'] = 'public';
            }

            $productId = DB::table('products')->insertGetId($insertPayload);

            $newPrimaryImageId = $this->storeImages($productId, $images);
            $this->applyPrimaryImage($productId, $newPrimaryImageId);

            $this->syncProductVariants($productId, $validated);

            return $productId;
        });
    }

    /**
     * @param  array<string, mixed>  $validated
     * @param  array<int, UploadedFile>  $newImages
     */
    public function updateProductCrud(int $productId, array $validated, array $newImages = []): void
    {
        $exists = DB::table('products')->where('id', $productId)->exists();
        if (! $exists) {
            throw ValidationException::withMessages([
                'product' => 'Product not found.',
            ]);
        }

        DB::transaction(function () use ($productId, $validated, $newImages): void {
            $slug = $this->resolveUniqueSlug((string) ($validated['slug'] ?? ''), (string) $validated['name'], $productId);
            $legacySku = $this->resolveLegacySku($validated['base_sku'] ?? null, $slug, $productId);

            $updatePayload = [
                'name' => $validated['name'],
                'slug' => $slug,
                'description' => $validated['description'] ?? null,
                'category_id' => $validated['category_id'] ?? null,
                'subcategory_id' => $validated['subcategory_id'] ?? null,
                'base_sku' => $validated['base_sku'] ?? null,
                'is_published' => (bool) ($validated['is_published'] ?? false),
                'updated_at' => now(),
            ];

            if (Schema::hasColumn('products', 'sku')) {
                $updatePayload['sku'] = $legacySku;
            }

            if (Schema::hasColumn('products', 'is_active')) {
                $updatePayload['is_active'] = (bool) ($validated['is_published'] ?? false);
            }

            DB::table('products')
                ->where('id', $productId)
                ->update($updatePayload);

            $this->deleteMarkedImages($productId, $validated['delete_image_ids'] ?? []);

            $newPrimaryImageId = $this->storeImages($productId, $newImages);

            $preferredPrimaryImageId = isset($validated['primary_image_id']) && $validated['primary_image_id'] !== ''
                ? (int) $validated['primary_image_id']
                : null;

            if (! $preferredPrimaryImageId && $newPrimaryImageId) {
                $preferredPrimaryImageId = $newPrimaryImageId;
            }

            $this->applyPrimaryImage($productId, $preferredPrimaryImageId);
            $this->syncProductVariants($productId, $validated);
        });
    }

    public function deleteProductCrud(int $productId): void
    {
        DB::transaction(function () use ($productId): void {
            $images = DB::table('product_image')
                ->where('product_id', $productId)
                ->pluck('file_path')
                ->all();

            foreach ($images as $filePath) {
                if (is_string($filePath) && $filePath !== '') {
                    Storage::disk('public')->delete($filePath);
                }
            }

            DB::table('products')
                ->where('id', $productId)
                ->delete();
        });
    }

    protected function resolveUniqueSlug(string $slugInput, string $name, ?int $ignoreProductId = null): string
    {
        $seed = trim($slugInput) !== '' ? $slugInput : $name;
        $baseSlug = Str::slug($seed);
        $baseSlug = $baseSlug !== '' ? $baseSlug : 'product';
        $slug = $baseSlug;
        $counter = 1;

        while (DB::table('products')
            ->where('slug', $slug)
            ->when($ignoreProductId, fn ($query) => $query->where('id', '!=', $ignoreProductId))
            ->exists()) {
            $slug = $baseSlug.'-'.$counter;
            $counter++;
        }

        return $slug;
    }

    protected function resolveLegacySku(mixed $baseSku, string $fallbackSlug, ?int $ignoreProductId = null): ?string
    {
        if (! Schema::hasColumn('products', 'sku')) {
            return null;
        }

        $base = trim((string) ($baseSku ?? ''));
        $seed = $base !== '' ? $base : $fallbackSlug;
        $seed = Str::of($seed)->replace('-', '_')->upper()->toString();
        $seed = preg_replace('/[^A-Z0-9_]/', '', $seed) ?: 'PRODUCT';
        $sku = $seed;
        $counter = 1;

        while (DB::table('products')
            ->where('sku', $sku)
            ->when($ignoreProductId, fn ($query) => $query->where('id', '!=', $ignoreProductId))
            ->exists()) {
            $sku = $seed.'_'.$counter;
            $counter++;
        }

        return $sku;
    }

    /**
     * @param  array<int, UploadedFile>  $images
     */
    protected function storeImages(int $productId, array $images): ?int
    {
        $sortOrder = ((int) DB::table('product_image')
            ->where('product_id', $productId)
            ->max('sort_order')) + 1;

        $newPrimaryImageId = null;

        foreach ($images as $image) {
            if (! $image instanceof UploadedFile) {
                continue;
            }

            $filePath = $image->store('products/'.$productId, 'public');
            $imageId = DB::table('product_image')->insertGetId([
                'product_id' => $productId,
                'file_path' => $filePath,
                'is_primary' => false,
                'sort_order' => $sortOrder,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if ($newPrimaryImageId === null) {
                $newPrimaryImageId = $imageId;
            }

            $sortOrder++;
        }

        return $newPrimaryImageId;
    }

    /**
     * @param  mixed  $imageIds
     */
    protected function deleteMarkedImages(int $productId, mixed $imageIds): void
    {
        $ids = collect(is_array($imageIds) ? $imageIds : [])
            ->map(fn ($id) => (int) $id)
            ->filter(fn ($id) => $id > 0)
            ->values();

        if ($ids->isEmpty()) {
            return;
        }

        $rows = DB::table('product_image')
            ->where('product_id', $productId)
            ->whereIn('id', $ids->all())
            ->get(['id', 'file_path']);

        foreach ($rows as $row) {
            if ($row->file_path) {
                Storage::disk('public')->delete($row->file_path);
            }
        }

        DB::table('product_image')
            ->where('product_id', $productId)
            ->whereIn('id', $ids->all())
            ->delete();
    }

    protected function applyPrimaryImage(int $productId, ?int $preferredPrimaryImageId = null): void
    {
        $query = DB::table('product_image')->where('product_id', $productId);

        $primaryImageId = null;

        if ($preferredPrimaryImageId) {
            $primaryImageId = DB::table('product_image')
                ->where('product_id', $productId)
                ->where('id', $preferredPrimaryImageId)
                ->value('id');
        }

        if (! $primaryImageId) {
            $primaryImageId = DB::table('product_image')
                ->where('product_id', $productId)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->value('id');
        }

        $query->update([
            'is_primary' => false,
            'updated_at' => now(),
        ]);

        if ($primaryImageId) {
            DB::table('product_image')
                ->where('product_id', $productId)
                ->where('id', $primaryImageId)
                ->update([
                    'is_primary' => true,
                    'updated_at' => now(),
                ]);
        }

        DB::table('products')
            ->where('id', $productId)
            ->update([
                'product_image_id' => $primaryImageId ?: null,
                'updated_at' => now(),
            ]);
    }

    /**
     * @param  array<string, mixed>  $validated
     */
    protected function syncProductVariants(int $productId, array $validated): void
    {
        $existingVariantIds = DB::table('product_variants')
            ->where('product_id', $productId)
            ->pluck('id')
            ->all();

        if (! empty($existingVariantIds)) {
            DB::table('variant_attributes')
                ->whereIn('product_variant_id', $existingVariantIds)
                ->delete();

            DB::table('product_variants')
                ->where('product_id', $productId)
                ->delete();
        }

        $skuRows = is_array($validated['variant_sku'] ?? null) ? $validated['variant_sku'] : [];
        $priceRows = is_array($validated['variant_price'] ?? null) ? $validated['variant_price'] : [];
        $stockRows = is_array($validated['variant_stock_quantity'] ?? null) ? $validated['variant_stock_quantity'] : [];
        $activeRows = is_array($validated['variant_is_active'] ?? null) ? $validated['variant_is_active'] : [];
        $attributeNameRows = is_array($validated['variant_attribute_name'] ?? null) ? $validated['variant_attribute_name'] : [];
        $attributeValueRows = is_array($validated['variant_attribute_value'] ?? null) ? $validated['variant_attribute_value'] : [];

        $rowCount = max(
            count($skuRows),
            count($priceRows),
            count($stockRows),
            count($activeRows),
            count($attributeNameRows),
            count($attributeValueRows),
        );

        $seenSkus = [];

        for ($index = 0; $index < $rowCount; $index++) {
            $sku = trim((string) ($skuRows[$index] ?? ''));
            $priceRaw = $priceRows[$index] ?? null;
            $stockRaw = $stockRows[$index] ?? null;
            $activeRaw = $activeRows[$index] ?? null;
            $attributeName = trim((string) ($attributeNameRows[$index] ?? ''));
            $attributeValue = trim((string) ($attributeValueRows[$index] ?? ''));

            if ($sku === '' && ($priceRaw === null || $priceRaw === '') && ($stockRaw === null || $stockRaw === '')) {
                continue;
            }

            if ($sku === '') {
                throw ValidationException::withMessages([
                    'variant_sku' => 'Variant SKU is required when variant details are provided.',
                ]);
            }

            if (in_array($sku, $seenSkus, true)) {
                throw ValidationException::withMessages([
                    'variant_sku' => "Duplicate SKU '{$sku}' in submitted variants.",
                ]);
            }

            $seenSkus[] = $sku;

            if (DB::table('product_variants')->where('sku', $sku)->exists()) {
                throw ValidationException::withMessages([
                    'variant_sku' => "SKU '{$sku}' already exists.",
                ]);
            }

            $variantId = DB::table('product_variants')->insertGetId([
                'product_id' => $productId,
                'sku' => $sku,
                'price' => $priceRaw === null || $priceRaw === '' ? 0 : (float) $priceRaw,
                'stock_quantity' => $stockRaw === null || $stockRaw === '' ? 0 : (int) $stockRaw,
                'is_active' => (string) $activeRaw !== '0',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if ($attributeName !== '' || $attributeValue !== '') {
                DB::table('variant_attributes')->insert([
                    'product_variant_id' => $variantId,
                    'attribute_name' => $attributeName !== '' ? $attributeName : 'attribute',
                    'attribute_value' => $attributeValue,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
