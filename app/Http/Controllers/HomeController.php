<?php

namespace App\Http\Controllers;

use App\Services\HomeService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController
{
    public function index(Request $request, HomeService $homeService): View
    {
        return view('prelogin.homepage', $homeService->viewData($request->user()));
    }
}
