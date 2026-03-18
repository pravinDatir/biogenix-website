<?php

use App\Http\Controllers\Authorization\AdminUserManagementController;
use App\Http\Controllers\Cart\CartController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Authorization\ImpersonationController;
use App\Http\Controllers\Authorization\RoleAndPermissionController;
use App\Http\Controllers\Invoice\ProformaInvoiceController;
use App\Http\Controllers\Invoice\QuotationController;
use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\Product\ProductCrudController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\SupportTicket\SupportTicketController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Flow completed
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index'])->name('home.page');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{productId}', [ProductController::class, 'productDetails'])->name('products.productDetails');
Route::get('/products/{productId}/technical-resources/{resourceId}/download', [ProductController::class, 'downloadTechnicalResource'])->name('products.technical-resources.download');

// flow incomplete 
 // Preview-only customer workspace pages (UI shells)
 Route::view('/customer/profile', 'customer.profile')->name('customer.profile.preview');
 Route::view('/customer/addresses', 'customer.addresses')->name('customer.addresses.preview');
 Route::view('/customer/support-tickets', 'support-tickets.preview')->name('customer.support.preview');

 // for testing PI flow only, will be removed later.
Route::get('/AdminhomeView', [HomeController::class, 'index2'])->name('home.page');

Route::get('/proforma/create', [ProformaInvoiceController::class, 'create'])->name('proforma.create');
Route::post('/proforma', [ProformaInvoiceController::class, 'store'])->name('proforma.store');
Route::get('/generate-quote', [QuotationController::class, 'showGenerateQuotePage'])->name('quotation.create');
Route::post('/generate-quote', [QuotationController::class, 'createQuotationAndDownloadPdf'])->name('quotation.store');
Route::get('/pi-quotation', [ProformaInvoiceController::class, 'showPiQuotationRequestPage'])->name('pi-quotation.generate');
Route::post('/pi-quotation', [ProformaInvoiceController::class, 'submitPiQuotationRequest'])->name('pi-quotation.store');
Route::view('/adminPanel/dashboard', 'adminPanel.dashboard')->name('adminPanel.dashboard');
Route::view('/adminPanel/products', 'adminPanel.products')->name('adminPanel.products');
Route::view('/adminPanel/products/create', 'adminPanel.products-create')->name('adminPanel.products.create');
Route::view('/adminPanel/pricing', 'adminPanel.pricing')->name('adminPanel.pricing');
Route::view('/adminPanel/pi-quotation', 'adminPanel.pi-quotation')->name('adminPanel.pi-quotation.index');
Route::view('/adminPanel/pi-quotation/create', 'adminPanel.pi-quotation-create')->name('adminPanel.pi-quotation.create');
Route::view('/adminPanel/orders', 'adminPanel.orders')->name('adminPanel.orders');
Route::view('/adminPanel/orders/view', 'adminPanel.order-details')->name('adminPanel.orders.view');
Route::get('/cart', [CartController::class, 'showCustomerCartPage'])->name('cart.page');
Route::get('/checkout', [CartController::class, 'showCustomerCheckoutPage'])->name('checkout.page');
Route::middleware('auth')->post('/checkout', [CartController::class, 'submitCustomerCheckoutOrder'])->name('checkout.submit');
Route::middleware('auth')->post('/checkout/buy-now', [CartController::class, 'startImmediateCheckout'])->name('checkout.buy-now');

Route::middleware('auth')->prefix('orders')->name('orders.')->group(function (): void {
    Route::get('/', [OrderController::class, 'showOrderCrud'])->name('index');
    Route::post('/', [OrderController::class, 'createOrder'])->name('store');
    Route::get('/{orderId}', [OrderController::class, 'getOrderById'])->name('show');
    Route::put('/{orderId}', [OrderController::class, 'editOrderById'])->name('update');
    Route::delete('/{orderId}', [OrderController::class, 'softDeleteOrderById'])->name('destroy');
});

Route::middleware('auth')->prefix('cart')->name('cart.')->group(function (): void {
    Route::get('/data', [CartController::class, 'showCart'])->name('show');
    Route::post('/items', [CartController::class, 'addToCart'])->name('items.store');
    Route::patch('/items/{cartItemId}', [CartController::class, 'updateCartItem'])->name('items.update');
    Route::delete('/items/{cartItemId}', [CartController::class, 'removeCartItem'])->name('items.delete');
    Route::post('/checkout', [CartController::class, 'checkoutCart'])->name('checkout');
});

// Route::middleware(['auth', 'active'])->group(function (): void {

   //Route::view('/homeAdmin', 'home')->name('home');
   Route::view('/about', 'prelogin.about')->name('about');
   Route::view('/contact', 'prelogin.contact')->name('contact');

   //Route::view('/login', 'auth.login')->name('login');
   Route::view('/signup', 'auth.signup')->name('signup');
   Route::view('/b2b-signup', 'auth.signup-b2b')->name('b2b.signup');
   Route::view('/forgot-password', 'auth.forgot-password')->name('forgot.password');
   Route::view('/book-meeting', 'prelogin.book-meeting')->name('book-meeting');

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
   Route::view('/refund-policy', 'legal.refund')->name('refund-policy');
   Route::view('/faq', 'legal.faq')->name('faq');
   Route::view('/order-confirmation', 'order.confirmation')->name('order.confirmation');
   Route::view('/maintenance', 'errors.503')->name('maintenance');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/proforma', [ProformaInvoiceController::class, 'index'])->middleware('auth')->name('proforma.index');
    Route::get('/proforma/{proformaId}/download', [ProformaInvoiceController::class, 'download'])->middleware('auth')->name('proforma.download');
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

    Route::middleware(['permission:users.manage'])->prefix('admin/roles')->name('admin.roles.')->group(function (): void {
        Route::get('/', [RoleAndPermissionController::class, 'getRole'])->name('index');
        Route::get('/{roleId}', [RoleAndPermissionController::class, 'getRole'])->name('show');
        Route::post('/', [RoleAndPermissionController::class, 'addRole'])->name('store');
        Route::put('/{roleId}', [RoleAndPermissionController::class, 'updateRole'])->name('update');
        Route::delete('/{roleId}', [RoleAndPermissionController::class, 'deleteRole'])->name('delete');
        Route::post('/permissions', [RoleAndPermissionController::class, 'createPermission'])->name('permissions.store');
        Route::put('/permissions/{permissionId}', [RoleAndPermissionController::class, 'updatePermission'])->name('permissions.update');
        Route::delete('/permissions/{permissionId}', [RoleAndPermissionController::class, 'deletePermission'])->name('permissions.delete');
        Route::post('/{roleId}/permissions', [RoleAndPermissionController::class, 'upsertPermissionsForRole'])->name('permissions.upsert');
    });

    Route::post('/admin/impersonate/{targetUserId}', [ImpersonationController::class, 'start'])
        ->middleware('permission:users.impersonate')
        ->name('admin.impersonation.start');
//});
