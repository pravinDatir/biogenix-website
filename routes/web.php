<?php

use App\Http\Controllers\AdminUserManagementController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImpersonationController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProformaInvoiceController;
use App\Http\Controllers\SupportTicketController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index'])->name('home.page');

 Route::get('/products', [ProductController::class, 'index'])->name('products.index');
// Route::get('/products/{productId}', [ProductController::class, 'show'])->name('products.show');

// Route::get('/proforma/create', [ProformaInvoiceController::class, 'create'])->name('proforma.create');
// Route::post('/proforma', [ProformaInvoiceController::class, 'store'])->name('proforma.store');

// Route::middleware(['auth', 'active'])->group(function (): void {

// Route::view('/', 'prelogin.homepage')->name('home');
   Route::view('/about', 'prelogin.about')->name('about');
   Route::view('/contact', 'prelogin.contact')->name('contact');

   Route::view('/login', 'auth.login')->name('login');
   Route::view('/signup', 'auth.signup')->name('signup');
   Route::view('/forgot-password', 'auth.forgot-password')->name('forgot.password');

// Route::view('/dashboard/customer', 'dashboard.customer')->name('customer.dashboard');
// Route::view('/dashboard/admin', 'dashboard.admin')->name('admin.dashboard');
// Route::get('/products', function () {
//     return view('prelogin.products');
// })->name('products');

// Route::get('/products/{id}', function ($id) {
//     return view('prelogin.product-details', compact('id'));
// })->name('product.details');

   Route::view('/privacy', 'legal.privacy')->name('privacy');
   Route::view('/terms', 'legal.terms')->name('terms');
   Route::view('/faq', 'legal.faq')->name('faq');


    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/proforma', [ProformaInvoiceController::class, 'index'])->name('proforma.index');
    Route::get('/support-tickets', [SupportTicketController::class, 'index'])->name('support-tickets.index');
    Route::post('/support-tickets', [SupportTicketController::class, 'store'])->name('support-tickets.store');
    Route::get('/support-tickets/{ticketId}', [SupportTicketController::class, 'show'])->name('support-tickets.show');
    Route::post('/support-tickets/{ticketId}/comments', [SupportTicketController::class, 'addComment'])->name('support-tickets.comments.store');
    Route::patch('/support-tickets/{ticketId}/status', [SupportTicketController::class, 'updateStatus'])->name('support-tickets.status.update');

    Route::post('/impersonation/stop', [ImpersonationController::class, 'stop'])->name('impersonation.stop');

    Route::middleware(['permission:users.manage'])->prefix('admin/users')->name('admin.users.')->group(function (): void {
        Route::get('/', [AdminUserManagementController::class, 'index'])->name('index');
        Route::post('/internal', [AdminUserManagementController::class, 'createInternal'])->name('internal.store');
        Route::post('/delegated-admin', [AdminUserManagementController::class, 'createDelegatedAdmin'])->name('delegated.store');
        Route::post('/{userId}/approve-b2b', [AdminUserManagementController::class, 'approveB2b'])->name('b2b.approve');
        Route::post('/{userId}/reject-b2b', [AdminUserManagementController::class, 'rejectB2b'])->name('b2b.reject');
        Route::post('/{userId}/permissions', [AdminUserManagementController::class, 'setUserPermission'])->name('permissions.set');
        Route::delete('/permissions/{overrideId}', [AdminUserManagementController::class, 'deleteUserPermission'])->name('permissions.delete');
        Route::post('/{userId}/scopes/company', [AdminUserManagementController::class, 'setDelegatedCompanyScope'])->name('scopes.set');
        Route::delete('/scopes/{scopeId}', [AdminUserManagementController::class, 'deleteDelegatedScope'])->name('scopes.delete');
    });

    Route::post('/admin/impersonate/{targetUserId}', [ImpersonationController::class, 'start'])
        ->middleware('permission:users.impersonate')
        ->name('admin.impersonation.start');
//});
