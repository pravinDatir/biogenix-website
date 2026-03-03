<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ProductService
{
    public function __construct(
        protected DataVisibilityService $dataVisibilityService,
    ) {
    }

    public function listVisibleProducts(?User $user, ?string $search = null, ?int $categoryId = null): LengthAwarePaginator
    {
        $query = $this->dataVisibilityService->visibleProductQuery($user);

        if ($search !== null && $search !== '') {
            $query->where(function ($builder) use ($search): void {
                $builder->where('products.name', 'like', '%'.$search.'%')
                    ->orWhere('products.sku', 'like', '%'.$search.'%');
            });
        }

        if ($categoryId !== null) {
            $query->where('products.category_id', $categoryId);
        }

        $products = $query
            ->orderBy('products.name')
            ->paginate(15)
            ->withQueryString();

        $products->setCollection(
            $products->getCollection()->map(function ($product) use ($user) {
                $price = $this->dataVisibilityService->resolvePrice((int) $product->id, $user);
                $product->visible_price = $price['amount'] ?? null;
                $product->visible_currency = $price['currency'] ?? null;
                $product->visible_price_type = $price['price_type'] ?? null;

                return $product;
            }),
        );

        return $products;
    }

    public function findVisibleProduct(?User $user, int $productId): ?object
    {
        return $this->dataVisibilityService->visibleProductQuery($user)
            ->where('products.id', $productId)
            ->first();
    }

    /**
     * @return array{amount: float, currency: string, price_type: string}|null
     */
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
}
