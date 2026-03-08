<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Throwable;

class DashboardController extends Controller
{
    // This renders the main dashboard for the logged-in user.
    public function index(Request $request, DashboardService $dashboardService): View
    {
        try {
            // Step 1: build the dashboard data for the current user.
            return view('dashboard', $dashboardService->dashboardData($request->user()));
        } catch (Throwable $exception) {
            Log::error('Failed to load dashboard.', ['error' => $exception->getMessage()]);

            return $this->viewWithError('dashboard', [
                'user' => $request->user(),
                'roleSlugs' => [],
                'permissions' => [],
                'departments' => [],
                'visibleProductsCount' => 0,
                'visiblePiCount' => 0,
            ], $exception, 'Unable to load dashboard.');
        }
    }
}
