<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use App\Services\Cart\CartService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class CartController extends Controller
{
    // This shows the cart page using the standard controller-to-view flow.
    public function showCustomerCartPage(Request $request, CartService $cartService): View
    {
        // Step 1: load the current cart once so the cart page opens with the latest backend data.
        $initialCart = $this->loadCurrentCartForPage($request, $cartService);

        // Step 2: return the cart page with the current cart seed for the storefront view.
        return view('pages.guest.cart', [
            'initialCart' => $initialCart,
        ]);
    }

    // This shows the checkout page using the standard controller-to-view flow.
    public function showCustomerCheckoutPage(Request $request, CartService $cartService): View
    {
        // Step 1: load the current cart once so the checkout page opens with the latest backend data.
        $initialCart = $this->loadCurrentCartForPage($request, $cartService);

        // Step 2: return the checkout page with the current cart seed for the storefront view.
        return view('pages.guest.checkout', [
            'initialCart' => $initialCart,
        ]);
    }

    // This starts the buy-now journey by adding the selected item into the current cart first.
    public function startImmediateCheckout(Request $request, CartService $cartService): RedirectResponse
    {
        try {
            // Step 1: validate the selected product details coming from the storefront buy-now action.
            $validatedSelectedItem = $request->validate([
                'product_id' => ['required', 'integer', 'exists:products,id'],
                'product_variant_id' => ['nullable', 'integer', 'exists:product_variants,id'],
                'quantity' => ['required', 'integer', 'min:1'],
            ]);

            // Step 2: add the selected item into the current cart so checkout includes both old and new items together.
            $cartService->addToCart($validatedSelectedItem, $request->user());

            // Step 3: send the customer straight to checkout to review and submit the full cart.
            return redirect()->route('checkout.page');
        } catch (Throwable $exception) {
            Log::error('Failed to start immediate checkout.', ['user_id' => $request->user()?->id, 'error' => $exception->getMessage()]);

            // Step 4: send the customer back with a business-friendly error message when buy now cannot start.
            return $this->redirectBackWithBusinessMessage($exception, 'Unable to start checkout right now.');
        }
    }

    // This submits the current cart as one final customer order.
    public function submitCustomerCheckoutOrder(Request $request, CartService $cartService): RedirectResponse
    {
        try {
            // Step 1: convert the current cart into a submitted order using the shared cart service.
            $submittedOrder = $cartService->checkoutCart([], $request->user());

            // Step 2: send the customer to confirmation with the newly created order summary in session.
            return redirect()
                ->route('order.confirmation')
                ->with('recentCheckoutOrder', $submittedOrder['order'])
                ->with('success', 'Order placed successfully.');
        } catch (Throwable $exception) {
            Log::error('Failed to submit customer checkout order.', ['user_id' => $request->user()?->id, 'error' => $exception->getMessage()]);

            // Step 3: send the customer back to checkout with a business-friendly error message when submission fails.
            return $this->redirectBackWithBusinessMessage($exception, 'Unable to place your order right now.');
        }
    }

    // This shows the order confirmation page after a successful checkout.
    public function showOrderConfirmationPage(Request $request): View
    {
        // Step 1: read the recently submitted order from session when the customer just completed checkout.
        $recentCheckoutOrder = $request->session()->get('recentCheckoutOrder');

        // Step 2: return the confirmation page with the recent order data for display.
        return view('order.confirmation', [
            'recentCheckoutOrder' => $recentCheckoutOrder,
        ]);
    }

    // This returns the current user's cart as JSON.
    public function showCart(Request $request, CartService $cartService): JsonResponse
    {
        try {
            // Step 1: load the current cart payload from the cart service.
            $cart = $cartService->showCart($request->user());

            // Step 2: return the cart payload as a success response.
            return response()->json([
                'status' => 'success',
                'message' => 'Cart loaded successfully.',
                'cart' => $cart,
            ]);
        } catch (Throwable $exception) {
            Log::error('Failed to return cart JSON response.', ['user_id' => $request->user()?->id, 'error' => $exception->getMessage()]);

            // Step 3: return a clean JSON error response.
            return $this->buildJsonErrorResponse($exception, 'Unable to load cart.');
        }
    }

    // This adds one product or variant to the current user's cart.
    public function addToCart(Request $request, CartService $cartService): JsonResponse
    {
        try {
            // Step 1: validate the add-to-cart payload.
            $validatedCartItem = $request->validate([
                'product_id' => ['required', 'integer', 'exists:products,id'],
                'product_variant_id' => ['nullable', 'integer', 'exists:product_variants,id'],
                'quantity' => ['required', 'integer', 'min:1'],
            ]);

            // Step 2: store the cart item using the cart service.
            $cart = $cartService->addToCart($validatedCartItem, $request->user());

            // Step 3: return the refreshed cart as JSON.
            return response()->json([
                'status' => 'success',
                'message' => 'Product added to cart successfully.',
                'cart' => $cart,
            ], 201);
        } catch (Throwable $exception) {
            Log::error('Failed to return add-to-cart JSON response.', ['user_id' => $request->user()?->id, 'error' => $exception->getMessage()]);

            // Step 4: return a clean JSON error response.
            return $this->buildJsonErrorResponse($exception, 'Unable to add product to cart.');
        }
    }

    // This updates the quantity of one existing cart item.
    public function updateCartItem(int $cartItemId, Request $request, CartService $cartService): JsonResponse
    {
        try {
            // Step 1: validate the requested cart quantity update.
            $validatedCartItem = $request->validate([
                'quantity' => ['required', 'integer', 'min:1'],
            ]);

            // Step 2: update the cart item using the cart service.
            $cart = $cartService->updateCartItem($cartItemId, $validatedCartItem, $request->user());

            // Step 3: return the refreshed cart as JSON.
            return response()->json([
                'status' => 'success',
                'message' => 'Cart item updated successfully.',
                'cart' => $cart,
            ]);
        } catch (Throwable $exception) {
            Log::error('Failed to return update-cart-item JSON response.', ['user_id' => $request->user()?->id, 'cart_item_id' => $cartItemId, 'error' => $exception->getMessage()]);

            // Step 4: return a clean JSON error response.
            return $this->buildJsonErrorResponse($exception, 'Unable to update cart item.');
        }
    }

    // This removes one existing cart item from the current user's cart.
    public function removeCartItem(int $cartItemId, Request $request, CartService $cartService): JsonResponse
    {
        try {
            // Step 1: remove the selected cart item using the cart service.
            $cart = $cartService->removeCartItem($cartItemId, $request->user());

            // Step 2: return the refreshed cart as JSON.
            return response()->json([
                'status' => 'success',
                'message' => 'Cart item removed successfully.',
                'cart' => $cart,
            ]);
        } catch (Throwable $exception) {
            Log::error('Failed to return remove-cart-item JSON response.', ['user_id' => $request->user()?->id, 'cart_item_id' => $cartItemId, 'error' => $exception->getMessage()]);

            // Step 3: return a clean JSON error response.
            return $this->buildJsonErrorResponse($exception, 'Unable to remove cart item.');
        }
    }

    // This checks out the current user's cart and creates one submitted order.
    public function checkoutCart(Request $request, CartService $cartService): JsonResponse
    {
        try {
            // Step 1: validate the optional extra checkout amounts and notes.
            $validatedCartCheckout = $request->validate([
                'shipping_amount' => ['nullable', 'numeric', 'min:0'],
                'adjustment_amount' => ['nullable', 'numeric'],
                'rounding_amount' => ['nullable', 'numeric'],
                'notes' => ['nullable', 'string', 'max:1000'],
            ]);

            // Step 2: convert the cart into one order using the cart service.
            $checkoutResult = $cartService->checkoutCart($validatedCartCheckout, $request->user());

            // Step 3: return the created order summary as JSON.
            return response()->json([
                'status' => 'success',
                'message' => 'Cart checked out successfully.',
                'order' => $checkoutResult['order'],
            ], 201);
        } catch (Throwable $exception) {
            Log::error('Failed to return checkout-cart JSON response.', ['user_id' => $request->user()?->id, 'error' => $exception->getMessage()]);

            // Step 4: return a clean JSON error response.
            return $this->buildJsonErrorResponse($exception, 'Unable to checkout cart.');
        }
    }

    // This converts known cart exceptions into a JSON error response.
    protected function buildJsonErrorResponse(Throwable $exception, string $defaultMessage): JsonResponse
    {
        // Step 1: choose the correct HTTP status code for the current exception.
        $statusCode = 500;

        if ($exception instanceof ValidationException || $exception instanceof QueryException) {
            $statusCode = 422;
        }

        if ($exception instanceof AuthorizationException) {
            $statusCode = 403;
        }

        if ($exception instanceof ModelNotFoundException || $exception instanceof NotFoundHttpException) {
            $statusCode = 404;
        }

        // Step 2: return the JSON error payload with optional validation errors.
        return response()->json([
            'status' => 'error',
            'message' => $this->resolveErrorMessage($exception, $defaultMessage),
            'errors' => $exception instanceof ValidationException ? $exception->errors() : [],
        ], $statusCode);
    }

    // This loads the initial cart data for full cart-related pages.
    protected function loadCurrentCartForPage(Request $request, CartService $cartService): ?array
    {
        // Step 1: skip database work when the current shopper is still browsing as a guest.
        if (! $request->user()) {
            return null;
        }

        try {
            // Step 2: load the current database cart once so the page can start with backend data already available.
            return $cartService->showCart($request->user());
        } catch (Throwable $exception) {
            Log::error('Failed to load current cart page data.', ['user_id' => $request->user()?->id, 'error' => $exception->getMessage()]);

            // Step 3: keep the page usable even when the cart seed cannot be prepared.
            return null;
        }
    }

    // This converts checkout failures into a standard redirect response for page-based flows.
    protected function redirectBackWithBusinessMessage(Throwable $exception, string $defaultMessage): RedirectResponse
    {
        // Step 1: return the validation details directly when the business rule failure came from form input or cart rules.
        if ($exception instanceof ValidationException) {
            return back()->withErrors($exception->errors())->withInput();
        }

        // Step 2: return one friendly fallback message for unexpected checkout issues.
        return back()->with('error', $defaultMessage);
    }
}
