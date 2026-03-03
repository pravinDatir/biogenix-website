<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request, ProductService $productService): View
    {
        $search = $request->filled('q') ? trim((string) $request->string('q')) : null;
        $categoryId = $request->filled('category_id') ? (int) $request->input('category_id') : null;
        $user = $request->user();
        $products = $productService->listVisibleProducts($user, $search, $categoryId);

        if (! $user) {
            $productService->logGuestActivity($request->session()->getId(), $request->path(), 'product_browse', [
                'search' => $request->input('q'),
                'category_id' => $request->input('category_id'),
            ]);
        }

        return view('products.index', [
            'products' => $products,
            'categories' => $productService->categories(),
        ]);
    }

    public function show(int $productId, Request $request, ProductService $productService): View
    {
        $user = $request->user();
        $product = $productService->findVisibleProduct($user, $productId);

        abort_if(! $product, 404);

        $price = $productService->resolvePrice($productId, $user);

        if (! $user) {
            $productService->logGuestActivity($request->session()->getId(), $request->path(), 'product_view', [
                'product_id' => $productId,
            ]);
        }

        return view('products.show', [
            'product' => $product,
            'price' => $price,
        ]);
    }
}
