<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use App\Services\Cart\CartService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class CartController extends Controller
{
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
}
