<?php

namespace App\Http\Controllers;

use App\Services\DataVisibilityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request, DataVisibilityService $dataVisibilityService): View
    {
        $query = $dataVisibilityService->visibleProductQuery($request->user());

        if ($request->filled('q')) {
            $search = trim((string) $request->string('q'));
            $query->where(function ($builder) use ($search): void {
                $builder->where('products.name', 'like', '%'.$search.'%')
                    ->orWhere('products.sku', 'like', '%'.$search.'%');
            });
        }

        if ($request->filled('category_id')) {
            $query->where('products.category_id', (int) $request->input('category_id'));
        }

        $products = $query
            ->orderBy('products.name')
            ->paginate(15)
            ->withQueryString();

        $products->setCollection(
            $products->getCollection()->map(function ($product) use ($dataVisibilityService, $request) {
                $price = $dataVisibilityService->resolvePrice((int) $product->id, $request->user());
                $product->visible_price = $price['amount'] ?? null;
                $product->visible_currency = $price['currency'] ?? null;
                $product->visible_price_type = $price['price_type'] ?? null;

                return $product;
            }),
        );

        if (! $request->user()) {
            $this->logGuestActivity($request, 'product_browse', [
                'search' => $request->input('q'),
                'category_id' => $request->input('category_id'),
            ]);
        }

        return view('products.index', [
            'products' => $products,
            'categories' => DB::table('categories')->orderBy('name')->get(),
        ]);
    }

    public function show(int $productId, Request $request, DataVisibilityService $dataVisibilityService): View
    {
        $product = $dataVisibilityService->visibleProductQuery($request->user())
            ->where('products.id', $productId)
            ->first();

        abort_if(! $product, 404);

        $price = $dataVisibilityService->resolvePrice($productId, $request->user());

        if (! $request->user()) {
            $this->logGuestActivity($request, 'product_view', [
                'product_id' => $productId,
            ]);
        }

        return view('products.show', [
            'product' => $product,
            'price' => $price,
        ]);
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    protected function logGuestActivity(Request $request, string $activityType, array $payload = []): void
    {
        DB::table('guest_activity_logs')->insert([
            'session_id' => $request->session()->getId(),
            'activity_type' => $activityType,
            'path' => $request->path(),
            'payload' => json_encode($payload),
            'created_at' => now(),
        ]);
    }
}
