<?php

namespace App\Http\Controllers;

use App\Services\RolePermissionService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController 
{
    public function index(Request $request, RolePermissionService $rolePermissionService): View
    {
        
        return view('home', [
            'roleSlugs' => $rolePermissionService->roleSlugsForUser($request->user()),
        ]);
    }
}
