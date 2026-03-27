<?php

use App\Http\Controllers\Authorization\AdminUserManagementController;
use App\Http\Controllers\BookMeeting\BookMeetingController;
use App\Http\Controllers\Cart\CartController;
use App\Http\Controllers\ContactUs\ContactUsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Faq\FaqController;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Authorization\RoleAndPermissionController;
use App\Http\Controllers\Authorization\SignupEmailOtpController;
use App\Http\Controllers\Invoice\ProformaInvoiceController;
use App\Http\Controllers\Invoice\QuotationController;
use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\Profile\CustomerAddressController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\Product\ProductCrudController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Quize\QuizeController;
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
 // Customer workspace routes that still use lightweight page wiring.
 Route::get('/customer/orders', [OrderController::class, 'showCustomerOrdersPage'])->middleware('auth')->name('customer.orders.preview');
 Route::view('/customer/support-tickets', 'userProfile.support-tickets.preview')->name('customer.support.preview');

 // for testing PI flow only, will be removed later.
Route::get('/AdminhomeView', [HomeController::class, 'index2'])->name('home.page');
Route::view('/test-animation', 'TestAnimation.index')->name('test-animation');


Route::get('/proforma/create', [ProformaInvoiceController::class, 'create'])->name('proforma.create');
Route::post('/proforma', [ProformaInvoiceController::class, 'store'])->name('proforma.store');
Route::get('/generate-quote', [QuotationController::class, 'showGenerateQuotePage'])->name('quotation.create');
Route::post('/generate-quote', [QuotationController::class, 'createQuotationAndDownloadPdf'])->name('quotation.store');
Route::get('/pi-quotation', [ProformaInvoiceController::class, 'showPiQuotationRequestPage'])->name('pi-quotation.generate');
Route::post('/pi-quotation', [ProformaInvoiceController::class, 'submitPiQuotationRequest'])->name('pi-quotation.store');
Route::view('/adminPanel/dashboard', 'adminPanel.dashboard')->name('adminPanel.dashboard');
Route::view('/adminPanel/products', 'adminPanel.products.index')->name('adminPanel.products');
Route::view('/adminPanel/products/create', 'adminPanel.products.create')->name('adminPanel.products.create');
Route::view('/adminPanel/pricing', 'adminPanel.pricing.index')->name('adminPanel.pricing');
Route::view('/adminPanel/pi-quotation', 'adminPanel.pi-quotation')->name('adminPanel.pi-quotation.index');
Route::view('/adminPanel/pi-quotation/create', 'adminPanel.pi-quotation-create')->name('adminPanel.pi-quotation.create');
Route::view('/adminPanel/orders', 'adminPanel.orders.index')->name('adminPanel.orders');
Route::view('/adminPanel/orders/view', 'adminPanel.orders.details')->name('adminPanel.orders.view');
Route::view('/adminPanel/customers', 'adminPanel.customers.index')->name('adminPanel.customers');
Route::view('/adminPanel/customer-directory', 'adminPanel.customers.directory')->name('adminPanel.customer-directory');
Route::group(['prefix' => 'adminPanel', 'as' => 'adminPanel.'], function () {
    Route::view('/role-permission', 'adminPanel.RolePermissions.index')->name('role-permission');
    Route::view('/role-permission/add-role', 'adminPanel.RolePermissions.add-role')->name('role-permission.add-role');
    Route::view('/role-permission/add-permission', 'adminPanel.RolePermissions.add-permission')->name('role-permission.add-permission');
    Route::view('/role-permission/assign-dept-role', 'adminPanel.RolePermissions.assign-dept-role')->name('role-permission.assign-dept-role');
    Route::view('/role-permission/add-override', 'adminPanel.RolePermissions.add-override')->name('role-permission.add-override');
    Route::view('/role-permission/add-delegation', 'adminPanel.RolePermissions.add-delegation')->name('role-permission.add-delegation');
    Route::view('/role-permission/grant-impersonation', 'adminPanel.RolePermissions.grant-impersonation')->name('role-permission.grant-impersonation');
});
Route::view('/adminPanel/support-tickets', 'adminPanel.support-tickets.index')->name('adminPanel.support-tickets');
Route::view('/adminPanel/ui-fields-modification', 'adminPanel.support-tickets.ui-fields-modification')->name('adminPanel.ui-fields-modification');
Route::view('/adminPanel/sync-monitor', 'adminPanel.sync-monitor.index')->name('adminPanel.sync-monitor');
Route::view('/adminPanel/global-settings', 'adminPanel.global-settings.index')->name('adminPanel.global-settings');
Route::get('/cart', [CartController::class, 'showCustomerCartPage'])->name('cart.page');
Route::get('/checkout', [CartController::class, 'showCustomerCheckoutPage'])->name('checkout.page');
Route::middleware('auth')->post('/checkout', [CartController::class, 'submitCustomerCheckoutOrder'])->name('checkout.submit');
Route::middleware('auth')->post('/checkout/buy-now', [CartController::class, 'startImmediateCheckout'])->name('checkout.buy-now');

Route::middleware('auth')->prefix('orders')->name('orders.')->group(function (): void {
    Route::get('/', [OrderController::class, 'showOrderCrud'])->name('index');
    Route::post('/', [OrderController::class, 'createOrder'])->name('store');
    Route::get('/reorder/checkout', [OrderController::class, 'showReOrderCheckoutPage'])->name('reorder.checkout');
    Route::post('/reorder/checkout', [OrderController::class, 'submitReOrderCheckout'])->name('reorder.checkout.submit');
    Route::post('/{orderId}/reorder', [OrderController::class, 'ReOrder'])->name('reorder');
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
   Route::get('/diagnostic-quiz', [QuizeController::class, 'index'])->name('diagnostic-quiz');
   Route::post('/diagnostic-quiz', [QuizeController::class, 'store'])->name('diagnostic-quiz.store');
   Route::get('/contact', [ContactUsController::class, 'index'])->name('contact');
   Route::post('/contact', [ContactUsController::class, 'store'])->name('contact.store');

   //Route::view('/login', 'auth.login')->name('login');
   Route::view('/signup', 'auth.signup')->name('signup');
   Route::view('/b2b-signup', 'auth.signup-b2b')->name('b2b.signup');
   Route::post('/signup/email-otp/send', [SignupEmailOtpController::class, 'sendOtp'])->name('signup.email-otp.send');
   Route::post('/signup/email-otp/verify', [SignupEmailOtpController::class, 'verifyOtp'])->name('signup.email-otp.verify');
   Route::view('/forgot-password', 'auth.forgot-password')->name('forgot.password');
   Route::get('/book-meeting', [BookMeetingController::class, 'index'])->name('book-meeting');
   Route::post('/book-meeting', [BookMeetingController::class, 'store'])->name('book-meeting.store');

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
   Route::get('/faq', [FaqController::class, 'index'])->name('faq');
   Route::view('/order-confirmation', 'order.confirmation')->name('order.confirmation');
   Route::view('/maintenance', 'errors.503')->name('maintenance');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/proforma', [ProformaInvoiceController::class, 'index'])->middleware('auth')->name('proforma.index');
    Route::get('/proforma/{proformaId}/download', [ProformaInvoiceController::class, 'download'])->middleware('auth')->name('proforma.download');
    Route::middleware('auth')->group(function (): void {
        Route::get('/customer/profile', [ProfileController::class, 'showMyProfilePage'])->name('customer.profile.preview');
        Route::post('/customer/profile', [ProfileController::class, 'updateMyProfileSection'])->name('customer.profile.update');
        Route::post('/customer/profile/password', [ProfileController::class, 'updateMyPassword'])->name('customer.profile.password.update');
        Route::get('/customer/addresses', [CustomerAddressController::class, 'index'])->name('customer.addresses.preview');
        Route::post('/customer/addresses', [CustomerAddressController::class, 'store'])->name('customer.addresses.store');
        Route::put('/customer/addresses/{addressId}', [CustomerAddressController::class, 'update'])->name('customer.addresses.update');
        Route::get('/support-tickets', [SupportTicketController::class, 'index'])->name('support-tickets.index');
        Route::post('/support-tickets', [SupportTicketController::class, 'store'])->name('support-tickets.store');
        Route::get('/support-tickets/{ticketId}', [SupportTicketController::class, 'show'])->name('support-tickets.show');
    });

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

//});
