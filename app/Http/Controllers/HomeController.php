<?php

namespace App\Http\Controllers;

use App\Services\HomeService;
use App\Services\Product\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class HomeController extends Controller
{
    // This renders the home page with summary content and product categories.
    public function index(Request $request, HomeService $homeService, ProductService $productService): View
    {
        try {
            // Step 1: load the product categories used on the home page.
            $productCategories = $productService->GetConfiguredCategories();

            Log::info('HomeController.index Product categories:', ['categories' => $productCategories]);

            // Step 2: merge home page data with the category list.
            return view('prelogin.homepage', array_merge(
                $homeService->viewData($request->user()),
                ['productCategories' => $productCategories],
            ));
        } catch (Throwable $exception) {
            Log::error('Failed to load home page.', ['error' => $exception->getMessage()]);

            return $this->viewWithError('prelogin.homepage', [
                'roleSlugs' => [],
                'productCategories' => collect(),
            ], $exception, 'Unable to load the home page.');
        }
    }

    // added for test only will be removed later
    public function index2(Request $request, HomeService $homeService, ProductService $productService): View
    {
        try {
            // Step 1: load the product categories used on the home page.
            $productCategories = $productService->categories();

            Log::info('HomeController.index Product categories:', ['categories' => $productCategories]);

            // Step 2: merge home page data with the category list.
            return view('home', array_merge(
                $homeService->viewData($request->user()),
                ['productCategories' => $productCategories],
            ));
        } catch (Throwable $exception) {
            Log::error('Failed to load home page.', ['error' => $exception->getMessage()]);

            return $this->viewWithError('prelogin.homepage', [
                'roleSlugs' => [],
                'productCategories' => collect(),
            ], $exception, 'Unable to load the home page.');
        }
    }
}
