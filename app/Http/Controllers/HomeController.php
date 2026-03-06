<?php

namespace App\Http\Controllers;

use App\Services\HomeService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Services\ProductService;
use Illuminate\Support\Facades\Log;

class HomeController
{
    public function index(Request $request, HomeService $homeService, ProductService $productService): View
    {
        $productCategories = $productService->categories();
        Log::info('HomeController.index Product categories:', ['categories' => $productCategories]);
        return view('prelogin.homepage', array_merge($homeService->viewData($request->user()), ['productCategories' => $productCategories]));
    }
}
