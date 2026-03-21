<?php

namespace App\Services\Cart;

use App\Models\Authorization\UserAddress;
use App\Models\Authorization\User;
use App\Models\Cart\Cart;
use App\Models\Cart\CartItem;
use App\Models\Order\Order;
use App\Models\Order\OrderAddress;
use App\Models\Product\ProductVariant;
use App\Services\Authorization\DataVisibilityService;
use App\Services\Notification\EmailNotificationService;
use App\Services\Pricing\PriceService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Throwable;

class CartService
{
    public function __construct(
        protected DataVisibilityService $dataVisibilityService,
        protected PriceService $priceService,
        protected EmailNotificationService $emailNotificationService,
    ) {
    }

    // This returns the current user's cart as a JSON-ready array.
    public function showCart(User $user): array
    {
        try {
            // Step 1: load the existing cart for the current user when one exists.
            $cart = $this->findCartForUser($user);

            // Step 2: build the JSON-ready cart payload from the loaded cart.
            return $this->buildCartResponse($cart, $user);
        } catch (Throwable $exception) {
            Log::error('Failed to show cart.', ['user_id' => $user->id, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This adds one product variant to the current user's cart.
    public function addToCart(array $validatedCartItem, User $user): array
    {
        try {
            // Step 1: find or create the current user's cart header row.
            $cart = $this->getOrCreateCartForUser($user);

            // Step 2: resolve the exact variant that should be stored in the cart.
            $productVariantId = $this->resolveRequestedVariantId($validatedCartItem, $user);

            // Step 3: resolve the visible live price for the selected variant.
            // Step 4: load any existing cart row for the same selected variant.
            $cartItem = $cart->items()
                ->where('product_variant_id', $productVariantId)
                ->first();

            // Step 5: calculate the final cart quantity after the add-to-cart action.
            $requestedQuantity = (int) $validatedCartItem['quantity'];
            $finalQuantity = $cartItem
                ? ((int) $cartItem->quantity + $requestedQuantity)
                : $requestedQuantity;

            // Step 6: resolve the final live price using the full requested cart quantity so bulk rules stay accurate.
            $resolvedVariantPrice = $this->dataVisibilityService->resolveVariantPrice($productVariantId, $user, $finalQuantity);

            if (! $resolvedVariantPrice) {
                throw ValidationException::withMessages([
                    'product_variant_id' => 'The selected product variant is not available for this user.',
                ]);
            }

            // Step 7: validate the final quantity against live price rules.
            $this->validateCartQuantity($finalQuantity, $resolvedVariantPrice);

            // Step 8: update the old cart row or create a new one.
            if ($cartItem) {
                $cartItem->update([
                    'quantity' => $finalQuantity,
                ]);
            } else {
                $cart->items()->create([
                    'product_variant_id' => $productVariantId,
                    'quantity' => $finalQuantity,
                ]);
            }

            // Step 9: keep the cart currency aligned with the current resolved price.
            $cart->update([
                'currency' => $resolvedVariantPrice['currency'] ?? 'INR',
            ]);

            // Step 10: log the successful add-to-cart action.
            Log::info('Product added to cart successfully.', [
                'user_id' => $user->id,
                'cart_id' => $cart->id,
                'product_variant_id' => $productVariantId,
                'quantity' => $finalQuantity,
            ]);

            // Step 11: return the refreshed cart payload.
            return $this->buildCartResponse($this->findCartForUser($user), $user);
        } catch (Throwable $exception) {
            Log::error('Failed to add product to cart.', ['user_id' => $user->id, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This updates the quantity of one existing cart item.
    public function updateCartItem(int $cartItemId, array $validatedCartItem, User $user): array
    {
        try {
            // Step 1: load the requested cart item and confirm cart ownership.
            $cartItem = $this->findCartItemForUser($cartItemId, $user);

            // Step 2: resolve the live current price for the selected variant.
            $updatedQuantity = (int) $validatedCartItem['quantity'];
            $resolvedVariantPrice = $this->dataVisibilityService->resolveVariantPrice((int) $cartItem->product_variant_id, $user, $updatedQuantity);

            if (! $resolvedVariantPrice) {
                throw ValidationException::withMessages([
                    'cart_item_id' => 'The selected cart item is no longer available for checkout.',
                ]);
            }

            // Step 3: validate the requested replacement quantity.
            $this->validateCartQuantity($updatedQuantity, $resolvedVariantPrice);

            // Step 4: save the new quantity and keep the cart currency aligned.
            $cartItem->update([
                'quantity' => $updatedQuantity,
            ]);

            $cartItem->cart->update([
                'currency' => $resolvedVariantPrice['currency'] ?? 'INR',
            ]);

            // Step 5: log the successful cart update.
            Log::info('Cart item updated successfully.', [
                'user_id' => $user->id,
                'cart_id' => $cartItem->cart_id,
                'cart_item_id' => $cartItemId,
                'quantity' => $updatedQuantity,
            ]);

            // Step 6: return the refreshed cart payload.
            return $this->buildCartResponse($this->findCartForUser($user), $user);
        } catch (Throwable $exception) {
            Log::error('Failed to update cart item.', ['user_id' => $user->id, 'cart_item_id' => $cartItemId, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This removes one existing cart item from the current user's cart.
    public function removeCartItem(int $cartItemId, User $user): array
    {
        try {
            // Step 1: load the requested cart item and confirm cart ownership.
            $cartItem = $this->findCartItemForUser($cartItemId, $user);

            // Step 2: keep the cart reference before the item is deleted.
            $cart = $cartItem->cart;

            // Step 3: remove the selected cart item row.
            $cartItem->delete();

            // Step 4: remove the cart header too when no items remain.
            if (! $cart->items()->exists()) {
                $cart->delete();

                Log::info('Last cart item removed and cart deleted.', [
                    'user_id' => $user->id,
                    'cart_id' => $cart->id,
                    'cart_item_id' => $cartItemId,
                ]);

                return $this->buildCartResponse(null, $user);
            }

            // Step 5: log the successful remove action.
            Log::info('Cart item removed successfully.', [
                'user_id' => $user->id,
                'cart_id' => $cart->id,
                'cart_item_id' => $cartItemId,
            ]);

            // Step 6: return the refreshed cart payload.
            return $this->buildCartResponse($this->findCartForUser($user), $user);
        } catch (Throwable $exception) {
            Log::error('Failed to remove cart item.', ['user_id' => $user->id, 'cart_item_id' => $cartItemId, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This converts the current user's cart into one submitted order and then clears the cart.
    public function checkoutCart(array $validatedCartCheckout, User $user): array
    {
        try {
            // Step 1: load the current user's cart with all cart items.
            $cart = $this->findCartForUser($user);

            if (! $cart || $cart->items->isEmpty()) {
                throw ValidationException::withMessages([
                    'cart' => 'Your cart is empty.',
                ]);
            }

            // Step 2: validate the coupon up front so the final checkout flow only continues with a real active offer.
            $validatedCoupon = $this->priceService->validateCouponCode($validatedCartCheckout['coupon_code'] ?? null);
            $couponCode = $validatedCoupon?->code;

            // Step 3: prepare final order item rows using the live current quantity-aware price.
            $preparedOrderItems = $this->prepareCheckoutOrderItems($cart, $user, $couponCode);

            // Step 4: stop the checkout when the entered coupon is real but not applicable to the current cart items.
            if ($couponCode && collect($preparedOrderItems)->sum(fn (array $preparedOrderItem) => (float) ($preparedOrderItem['item_snapshot']['coupon_discount_amount'] ?? 0) * (int) ($preparedOrderItem['quantity'] ?? 1)) <= 0) {
                throw ValidationException::withMessages([
                    'coupon_code' => 'The selected coupon does not apply to the current cart.',
                ]);
            }

            // Step 5: calculate final order totals from the prepared order item rows.
            $orderTotals = $this->calculateCheckoutTotals($validatedCartCheckout, $preparedOrderItems);

            // Step 6: create the order and clear the cart inside one transaction.
            $order = DB::transaction(function () use ($cart, $user, $validatedCartCheckout, $preparedOrderItems, $orderTotals): Order {
                $order = Order::query()->create([
                    'placed_by_user_id' => $user->id,
                    'company_id' => $user->company_id,
                    'status' => 'submitted',
                    'currency' => $orderTotals['currency'],
                    'subtotal_amount' => $orderTotals['subtotal_amount'],
                    'tax_amount' => $orderTotals['tax_amount'],
                    'discount_amount' => $orderTotals['discount_amount'],
                    'shipping_amount' => $orderTotals['shipping_amount'],
                    'adjustment_amount' => $orderTotals['adjustment_amount'],
                    'rounding_amount' => $orderTotals['rounding_amount'],
                    'total_amount' => $orderTotals['total_amount'],
                    'pricing_snapshot' => $orderTotals['pricing_snapshot'],
                    'notes' => $validatedCartCheckout['notes'] ?? null,
                    'submitted_at' => now(),
                    'cancelled_at' => null,
                ]);

                $createdOrderItems = [];

                foreach ($preparedOrderItems as $preparedOrderItem) {
                    $createdOrderItems[] = $order->items()->create($preparedOrderItem);
                }

                // Step 7: store the final discount breakdown so operations can trace why the final order value changed.
                $this->storeCheckoutDiscountLines($order, collect($createdOrderItems), $preparedOrderItems);

                // Step 8: store the selected shipping and billing addresses on the order so operations can dispatch correctly.
                $this->storeCheckoutOrderAddresses($order, $user, $validatedCartCheckout);

                $cart->items()->delete();
                $cart->delete();

                return Order::query()
                    ->with([
                        'placedByUser:id,name,email,user_type',
                        'company:id,name,company_type',
                        'addresses',
                        'shippingAddress',
                        'billingAddress',
                        'items' => fn ($builder) => $builder->orderBy('sort_order')->orderBy('id'),
                        'items.product:id,name,sku',
                        'items.variant:id,product_id,sku,variant_name',
                    ])
                    ->findOrFail($order->id);
            });

            // Step 8: log the successful checkout action with the final order id.
            Log::info('Cart checked out successfully.', [
                'user_id' => $user->id,
                'order_id' => $order->id,
            ]);

            // Step 9: send the customer email after the order is safely created, while keeping the order success independent from email provider issues.
            $this->sendOrderSubmittedEmail($user, $order);

            // Step 10: return the final order summary as JSON-ready data.
            return [
                'order' => [
                    'id' => $order->id,
                    'status' => $order->status,
                    'currency' => $order->currency,
                    'subtotal_amount' => (float) $order->subtotal_amount,
                    'tax_amount' => (float) $order->tax_amount,
                    'discount_amount' => (float) $order->discount_amount,
                    'shipping_amount' => (float) $order->shipping_amount,
                    'adjustment_amount' => (float) $order->adjustment_amount,
                    'rounding_amount' => (float) $order->rounding_amount,
                    'total_amount' => (float) $order->total_amount,
                    'notes' => $order->notes,
                    'items_count' => $order->items->count(),
                    'items' => $order->items->map(function ($orderItem) {
                        return [
                            'id' => $orderItem->id,
                            'product_name' => $orderItem->product_name,
                            'variant_name' => $orderItem->variant_name,
                            'sku' => $orderItem->sku,
                            'quantity' => (int) $orderItem->quantity,
                            'unit_price' => (float) $orderItem->unit_price,
                            'tax_amount' => (float) $orderItem->tax_amount,
                            'total_amount' => (float) $orderItem->total_amount,
                        ];
                    })->values()->all(),
                ],
            ];
        } catch (Throwable $exception) {
            Log::error('Failed to checkout cart.', ['user_id' => $user->id, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This sends the order-submitted customer email without interrupting a successful checkout.
    protected function sendOrderSubmittedEmail(User $user, Order $order): void
    {
        try {
            // Step 1: skip the send cleanly when the account does not have an email address yet.
            if (! filled($user->email)) {
                Log::warning('Order submitted email skipped because the user email is empty.', [
                    'user_id' => $user->id,
                    'order_id' => $order->id,
                ]);

                return;
            }

            // Step 2: send the business confirmation email using the shared notification service.
            $this->emailNotificationService->sendOrderSubmittedConfirmation($user, $order);
        } catch (Throwable $exception) {
            Log::error('Order submitted email could not be delivered after checkout.', [
                'user_id' => $user->id,
                'order_id' => $order->id,
                'error' => $exception->getMessage(),
            ]);
        }
    }

    // This stores the selected shipping and billing addresses for a submitted order.
    protected function storeCheckoutOrderAddresses(Order $order, User $user, array $validatedCartCheckout): void
    {
        try {
            // Step 1: resolve the checkout address that the customer selected or entered on the page.
            $checkoutAddressData = $this->resolveCheckoutAddressData($user, $validatedCartCheckout);
            $selectedAddressSource = (string) ($validatedCartCheckout['selected_address_source'] ?? 'existing');

            // Step 2: create the shipping address row exactly as it will be used for dispatch and delivery.
            $order->addresses()->create($this->buildOrderAddressPayload(
                $order,
                'shipping',
                $checkoutAddressData,
                $user,
                $validatedCartCheckout
            ));

            // Step 3: create the billing address row too so later invoice and finance flows have a stable stored reference.
            $order->addresses()->create($this->buildOrderAddressPayload(
                $order,
                'billing',
                $checkoutAddressData,
                $user,
                $validatedCartCheckout
            ));

            // Step 4: write one business log so the team can confirm whether checkout reused an existing address or saved a brand-new one.
            Log::info('Checkout order addresses stored successfully.', [
                'user_id' => $user->id,
                'order_id' => $order->id,
                'selected_address_source' => $selectedAddressSource,
                'user_address_id' => $checkoutAddressData['user_address']->id ?? null,
            ]);
        } catch (Throwable $exception) {
            Log::error('Failed to store checkout order addresses.', [
                'user_id' => $user->id,
                'order_id' => $order->id,
                'error' => $exception->getMessage(),
            ]);

            throw $exception;
        }
    }

    // This resolves the final checkout address from either an existing saved address or the new-address form.
    protected function resolveCheckoutAddressData(User $user, array $validatedCartCheckout): array
    {
        try {
            $selectedAddressSource = (string) ($validatedCartCheckout['selected_address_source'] ?? 'existing');

            // Step 1: reuse the selected saved address when the customer picked an existing address from the address book.
            if ($selectedAddressSource === 'existing') {
                $selectedUserAddress = UserAddress::query()
                    ->where('user_id', $user->id)
                    ->whereKey((int) ($validatedCartCheckout['selected_user_address_id'] ?? 0))
                    ->first();

                if (! $selectedUserAddress) {
                    throw ValidationException::withMessages([
                        'selected_user_address_id' => 'Please select one saved address for checkout.',
                    ]);
                }

                // Step 1A: log that checkout reused an already-saved address and did not create a fresh address-book entry.
                Log::info('Checkout is using an existing saved user address.', [
                    'user_id' => $user->id,
                    'user_address_id' => $selectedUserAddress->id,
                ]);

                return [
                    'user_address' => $selectedUserAddress,
                    'address_line1' => $selectedUserAddress->line1,
                    'address_line2' => $selectedUserAddress->line2,
                    'city' => $selectedUserAddress->city,
                    'state' => $selectedUserAddress->state,
                    'postal_code' => $selectedUserAddress->postal_code,
                    'country' => $selectedUserAddress->country ?: 'India',
                    'contact_phone' => $user->phone,
                    'address_label' => $selectedUserAddress->line2,
                ];
            }

            // Step 2: create a saved user address first when the customer entered a brand new dispatch address on checkout.
            $createdUserAddress = $this->saveNewCheckoutAddressForUser($user, $validatedCartCheckout);

            return [
                'user_address' => $createdUserAddress,
                'address_line1' => $createdUserAddress->line1,
                'address_line2' => $createdUserAddress->line2,
                'city' => $createdUserAddress->city,
                'state' => $createdUserAddress->state,
                'postal_code' => $createdUserAddress->postal_code,
                'country' => $createdUserAddress->country ?: 'India',
                'contact_phone' => $validatedCartCheckout['new_address_phone'] ?? $user->phone,
                'address_label' => $validatedCartCheckout['new_address_label'] ?? null,
            ];
        } catch (Throwable $exception) {
            Log::error('Failed to resolve checkout address data.', [
                'user_id' => $user->id,
                'selected_address_source' => $validatedCartCheckout['selected_address_source'] ?? null,
                'error' => $exception->getMessage(),
            ]);

            throw $exception;
        }
    }

    // This saves a new checkout address into the user address book before it is used on the order.
    protected function saveNewCheckoutAddressForUser(User $user, array $validatedCartCheckout): UserAddress
    {
        try {
            $userHasSavedAddresses = $user->addresses()->exists();

            // Step 1: save the new address in the shared user address book so it becomes reusable in later checkouts.
            $createdUserAddress = $user->addresses()->create([
                'line1' => trim((string) ($validatedCartCheckout['new_address_line1'] ?? '')),
                'line2' => filled($validatedCartCheckout['new_address_label'] ?? null) ? trim((string) $validatedCartCheckout['new_address_label']) : null,
                'city' => trim((string) ($validatedCartCheckout['new_address_city'] ?? '')),
                'state' => trim((string) ($validatedCartCheckout['new_address_state'] ?? '')),
                'postal_code' => trim((string) ($validatedCartCheckout['new_address_postal_code'] ?? '')),
                'country' => filled($validatedCartCheckout['new_address_country'] ?? null) ? trim((string) $validatedCartCheckout['new_address_country']) : 'India',
                'is_default_shipping' => ! $userHasSavedAddresses,
                'is_default_billing' => ! $userHasSavedAddresses,
            ]);

            // Step 2: log the new address-book save so support can verify that only fresh checkout addresses create user_address rows.
            Log::info('New checkout address saved to user address book.', [
                'user_id' => $user->id,
                'user_address_id' => $createdUserAddress->id,
            ]);

            return $createdUserAddress;
        } catch (Throwable $exception) {
            Log::error('Failed to save new checkout address for user.', [
                'user_id' => $user->id,
                'error' => $exception->getMessage(),
            ]);

            throw $exception;
        }
    }

    // This builds one order_addresses payload from the chosen checkout address and the current customer context.
    protected function buildOrderAddressPayload(Order $order, string $addressType, array $checkoutAddressData, User $user, array $validatedCartCheckout): array
    {
        try {
            $companyName = filled($validatedCartCheckout['registered_business_name'] ?? null)
                ? trim((string) $validatedCartCheckout['registered_business_name'])
                : ($user->company?->legal_name ?: $user->company?->name);

            // Step 1: keep the business GST details only on billing flows for B2B customers.
            $gstin = $addressType === 'billing' && $user->isB2b()
                ? (filled($validatedCartCheckout['gstin'] ?? null) ? trim((string) $validatedCartCheckout['gstin']) : ($user->company?->gst_number))
                : null;

            // Step 2: use the same selected address lines for both shipping and billing to keep the current checkout flow simple.
            return [
                'order_id' => $order->id,
                'address_type' => $addressType,
                'contact_name' => $user->name,
                'company_name' => $user->isB2b() ? $companyName : null,
                'email' => $user->email,
                'phone' => filled($checkoutAddressData['contact_phone'] ?? null) ? trim((string) $checkoutAddressData['contact_phone']) : $user->phone,
                'gstin' => $gstin,
                'line1' => trim((string) ($checkoutAddressData['address_line1'] ?? '')),
                'line2' => filled($checkoutAddressData['address_line2'] ?? null) ? trim((string) $checkoutAddressData['address_line2']) : null,
                'landmark' => filled($checkoutAddressData['address_label'] ?? null) ? trim((string) $checkoutAddressData['address_label']) : null,
                'city' => trim((string) ($checkoutAddressData['city'] ?? '')),
                'state' => trim((string) ($checkoutAddressData['state'] ?? '')),
                'postal_code' => trim((string) ($checkoutAddressData['postal_code'] ?? '')),
                'country_code' => $this->normalizeCountryCode($checkoutAddressData['country'] ?? 'India'),
            ];
        } catch (Throwable $exception) {
            Log::error('Failed to build order address payload.', [
                'user_id' => $user->id,
                'order_id' => $order->id,
                'address_type' => $addressType,
                'error' => $exception->getMessage(),
            ]);

            throw $exception;
        }
    }

    // This converts the entered country into the two-character code expected by order_addresses.
    protected function normalizeCountryCode(?string $country): string
    {
        $normalizedCountry = strtoupper(trim((string) $country));

        return match ($normalizedCountry) {
            '', 'INDIA', 'IN' => 'IN',
            default => substr($normalizedCountry, 0, 2),
        };
    }

    // This loads the current user's cart with relations.
    protected function findCartForUser(User $user): ?Cart
    {
        try {
            // Step 1: load the cart with the image data needed by the storefront cart screens.
            return Cart::query()
                ->with([
                    'items' => fn ($builder) => $builder->orderBy('id'),
                    'items.variant.product.primaryImage',
                ])
                ->where('user_id', $user->id)
                ->first();
        } catch (Throwable $exception) {
            Log::error('Failed to find cart for user.', ['user_id' => $user->id, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This finds or creates one cart row for the current user.
    protected function getOrCreateCartForUser(User $user): Cart
    {
        try {
            // Step 1: create the cart row only when it does not already exist.
            return Cart::query()->firstOrCreate(
                ['user_id' => $user->id],
                ['currency' => 'INR'],
            );
        } catch (Throwable $exception) {
            Log::error('Failed to find or create cart for user.', ['user_id' => $user->id, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This loads one cart item and confirms it belongs to the current user.
    protected function findCartItemForUser(int $cartItemId, User $user): CartItem
    {
        try {
            // Step 1: query the selected cart item with cart ownership and relations.
            $cartItem = CartItem::query()
                ->with(['cart', 'variant.product'])
                ->whereKey($cartItemId)
                ->whereHas('cart', function ($builder) use ($user): void {
                    $builder->where('user_id', $user->id);
                })
                ->first();

            if (! $cartItem) {
                throw (new ModelNotFoundException())->setModel(CartItem::class, [$cartItemId]);
            }

            // Step 2: return the owned cart item row.
            return $cartItem;
        } catch (Throwable $exception) {
            Log::error('Failed to find cart item for user.', ['user_id' => $user->id, 'cart_item_id' => $cartItemId, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This resolves which product variant should be stored in the cart.
    protected function resolveRequestedVariantId(array $validatedCartItem, User $user): int
    {
        try {
            // Step 1: use the provided product variant when the client sends one.
            if (! empty($validatedCartItem['product_variant_id'])) {
                $selectedVariant = ProductVariant::query()
                    ->whereKey((int) $validatedCartItem['product_variant_id'])
                    ->where('product_id', (int) $validatedCartItem['product_id'])
                    ->where('is_active', true)
                    ->first();

                if (! $selectedVariant) {
                    throw ValidationException::withMessages([
                        'product_variant_id' => 'The selected product variant does not belong to the selected product.',
                    ]);
                }

                return (int) $selectedVariant->id;
            }

            // Step 2: fall back to the first visible priced variant for the selected product.
            $resolvedProductPrice = $this->dataVisibilityService->resolvePrice((int) $validatedCartItem['product_id'], $user);

            if (! $resolvedProductPrice) {
                throw ValidationException::withMessages([
                    'product_id' => 'The selected product is not available for this user.',
                ]);
            }

            // Step 3: return the resolved default variant id.
            return (int) $resolvedProductPrice['product_variant_id'];
        } catch (Throwable $exception) {
            Log::error('Failed to resolve requested cart variant.', ['user_id' => $user->id, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This validates cart quantity using the live current variant rules.
    protected function validateCartQuantity(int $quantity, array $resolvedVariantPrice): void
    {
        try {
            // Step 1: read the live min quantity, max quantity, and lot size from the selected sellable variant.
            $minOrderQuantity = max(1, (int) ($resolvedVariantPrice['min_order_quantity'] ?? 1));
            $maxOrderQuantity = $resolvedVariantPrice['max_order_quantity'] === null ? null : (int) $resolvedVariantPrice['max_order_quantity'];
            $lotSize = max(1, (int) ($resolvedVariantPrice['lot_size'] ?? 1));

            // Step 2: block quantities below the configured minimum.
            if ($quantity < $minOrderQuantity) {
                throw ValidationException::withMessages([
                    'quantity' => "Quantity must be at least {$minOrderQuantity}.",
                ]);
            }

            // Step 3: block quantities above the configured maximum when one exists.
            if ($maxOrderQuantity !== null && $quantity > $maxOrderQuantity) {
                throw ValidationException::withMessages([
                    'quantity' => "Quantity must not exceed {$maxOrderQuantity}.",
                ]);
            }

            // Step 4: block quantities that do not match the configured lot size.
            if ($lotSize > 1 && $quantity % $lotSize !== 0) {
                throw ValidationException::withMessages([
                    'quantity' => "Quantity must be in multiples of {$lotSize}.",
                ]);
            }
        } catch (Throwable $exception) {
            Log::error('Failed to validate cart quantity.', ['quantity' => $quantity, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This builds the full cart payload used by all JSON responses.
    protected function buildCartResponse(?Cart $cart, User $user): array
    {
        try {
            // Step 1: return a clean empty cart structure when no cart exists.
            if (! $cart) {
                return [
                    'id' => null,
                    'user_id' => $user->id,
                    'currency' => 'INR',
                    'items_count' => 0,
                    'subtotal_amount' => 0,
                    'tax_amount' => 0,
                    'total_amount' => 0,
                    'items' => [],
                ];
            }

            // Step 2: build the live current cart item payloads from all saved item rows.
            $cartItems = $cart->items
                ->map(function (CartItem $cartItem) use ($user): array {
                    return $this->buildCartItemResponse($cartItem, $user);
                })
                ->values()
                ->all();

            // Step 3: calculate current totals from the live cart item payloads.
            $cartTotals = $this->calculateCartTotals($cartItems);

            // Step 4: return the final cart response array.
            return [
                'id' => $cart->id,
                'user_id' => $cart->user_id,
                'currency' => $cartTotals['currency'],
                'items_count' => count($cartItems),
                'subtotal_amount' => $cartTotals['subtotal_amount'],
                'tax_amount' => $cartTotals['tax_amount'],
                'total_amount' => $cartTotals['total_amount'],
                'items' => $cartItems,
            ];
        } catch (Throwable $exception) {
            Log::error('Failed to build cart response.', ['user_id' => $user->id, 'cart_id' => $cart?->id, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This builds one JSON-ready cart item payload with live price values.
    protected function buildCartItemResponse(CartItem $cartItem, User $user): array
    {
        try {
            // Step 1: load the product, variant, and primary image used across the cart UI.
            $productVariant = $cartItem->variant;
            $product = $productVariant?->product;
            $imagePath = $product?->primaryImage?->file_path;

            // Step 2: resolve the live current price for the selected variant.
            $resolvedVariantPrice = $this->dataVisibilityService->resolveVariantPrice((int) $cartItem->product_variant_id, $user, (int) $cartItem->quantity);

            if (! $resolvedVariantPrice) {
                throw ValidationException::withMessages([
                    'cart_item_id' => 'One cart item is no longer available with current pricing.',
                ]);
            }

            // Step 3: calculate the cart line totals from the live price.
            $unitPrice = round((float) ($resolvedVariantPrice['amount'] ?? 0), 4);
            $unitTaxAmount = round((float) ($resolvedVariantPrice['tax_amount'] ?? 0), 4);
            $unitPriceAfterGst = round((float) ($resolvedVariantPrice['price_after_gst'] ?? 0), 4);
            $lineSubtotal = round($unitPrice * (int) $cartItem->quantity, 4);
            $lineTaxAmount = round($unitTaxAmount * (int) $cartItem->quantity, 4);
            $lineTotal = round($unitPriceAfterGst * (int) $cartItem->quantity, 4);

            // Step 4: return a cart item payload that the storefront can render without extra lookups.
            return [
                'id' => $cartItem->id,
                'product_id' => $product?->id,
                'product_variant_id' => $cartItem->product_variant_id,
                'product_name' => $product?->name,
                'variant_name' => $productVariant?->variant_name,
                'sku' => $productVariant?->sku,
                'image_url' => filled($imagePath) ? asset($imagePath) : null,
                'quantity' => (int) $cartItem->quantity,
                'currency' => $resolvedVariantPrice['currency'] ?? 'INR',
                'price_type' => $resolvedVariantPrice['price_type'] ?? null,
                'unit_price' => $unitPrice,
                'base_unit_price' => round((float) ($resolvedVariantPrice['base_amount'] ?? $unitPrice), 4),
                'gst_rate' => round((float) ($resolvedVariantPrice['gst_rate'] ?? 0), 4),
                'tax_amount' => $lineTaxAmount,
                'unit_price_after_gst' => $unitPriceAfterGst,
                'line_subtotal' => $lineSubtotal,
                'line_total' => $lineTotal,
                'discount_amount' => round((float) ($resolvedVariantPrice['discount_amount'] ?? 0) * (int) $cartItem->quantity, 4),
                'min_order_quantity' => (int) ($resolvedVariantPrice['min_order_quantity'] ?? 1),
                'max_order_quantity' => $resolvedVariantPrice['max_order_quantity'] === null ? null : (int) $resolvedVariantPrice['max_order_quantity'],
                'lot_size' => (int) ($resolvedVariantPrice['lot_size'] ?? 1),
            ];
        } catch (Throwable $exception) {
            Log::error('Failed to build cart item response.', ['user_id' => $user->id, 'cart_item_id' => $cartItem->id, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This calculates cart totals from all current cart item payloads.
    protected function calculateCartTotals(array $cartItems): array
    {
        try {
            // Step 1: use INR when the cart currently has no items.
            if ($cartItems === []) {
                return [
                    'currency' => 'INR',
                    'subtotal_amount' => 0,
                    'tax_amount' => 0,
                    'total_amount' => 0,
                ];
            }

            // Step 2: sum all line values from the built cart item payloads.
            $currency = (string) ($cartItems[0]['currency'] ?? 'INR');
            $subtotalAmount = round(collect($cartItems)->sum('line_subtotal'), 4);
            $taxAmount = round(collect($cartItems)->sum('tax_amount'), 4);
            $totalAmount = round(collect($cartItems)->sum('line_total'), 4);

            // Step 3: return the calculated cart totals.
            return [
                'currency' => $currency,
                'subtotal_amount' => $subtotalAmount,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
            ];
        } catch (Throwable $exception) {
            Log::error('Failed to calculate cart totals.', ['error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This prepares final order item rows from the current cart during checkout.
    protected function prepareCheckoutOrderItems(Cart $cart, User $user, ?string $couponCode = null): array
    {
        try {
            // Step 1: prepare an array that will hold all final order item rows.
            $preparedOrderItems = [];

            // Step 2: convert each cart item into one final order item payload.
            foreach ($cart->items as $index => $cartItem) {
                $productVariant = $cartItem->variant;
                $product = $productVariant?->product;

                if (! $productVariant || ! $product) {
                    throw ValidationException::withMessages([
                        'cart' => 'One cart item is no longer available.',
                    ]);
                }

                $resolvedVariantPrice = $this->dataVisibilityService->resolveVariantPrice((int) $cartItem->product_variant_id, $user, (int) $cartItem->quantity, $couponCode);

                if (! $resolvedVariantPrice) {
                    throw ValidationException::withMessages([
                        'cart' => 'One cart item is no longer available for checkout.',
                    ]);
                }

                $this->validateCartQuantity((int) $cartItem->quantity, $resolvedVariantPrice);

                $unitPrice = round((float) ($resolvedVariantPrice['amount'] ?? 0), 4);
                $unitBasePrice = round((float) ($resolvedVariantPrice['base_amount'] ?? $unitPrice), 4);
                $unitTaxAmount = round((float) ($resolvedVariantPrice['tax_amount'] ?? 0), 4);
                $unitPriceAfterGst = round((float) ($resolvedVariantPrice['price_after_gst'] ?? 0), 4);
                $subtotalAmount = round($unitPrice * (int) $cartItem->quantity, 4);
                $taxAmount = round($unitTaxAmount * (int) $cartItem->quantity, 4);
                $totalAmount = round($unitPriceAfterGst * (int) $cartItem->quantity, 4);
                $lineDiscountAmount = round((float) ($resolvedVariantPrice['discount_amount'] ?? 0) * (int) $cartItem->quantity, 4);

                $preparedOrderItems[] = [
                    'product_id' => $product->id,
                    'product_variant_id' => $productVariant->id,
                    'sku' => $productVariant->sku,
                    'product_name' => $product->name,
                    'variant_name' => $productVariant->variant_name,
                    'description' => 'Created from cart checkout.',
                    'quantity' => (int) $cartItem->quantity,
                    'unit_price' => $unitPrice,
                    'subtotal_amount' => $subtotalAmount,
                    'discount_amount' => $lineDiscountAmount,
                    'tax_amount' => $taxAmount,
                    'total_amount' => $totalAmount,
                    'sort_order' => $index,
                    'item_snapshot' => [
                        'source' => 'cart_checkout',
                        'currency' => $resolvedVariantPrice['currency'] ?? 'INR',
                        'price_type' => $resolvedVariantPrice['price_type'] ?? null,
                        'base_unit_price' => $unitBasePrice,
                        'pricing_stage' => $resolvedVariantPrice['pricing_stage'] ?? 'base_price',
                        'gst_rate' => round((float) ($resolvedVariantPrice['gst_rate'] ?? 0), 4),
                        'unit_tax_amount' => $unitTaxAmount,
                        'unit_price_after_gst' => $unitPriceAfterGst,
                        'unit_discount_amount' => round((float) ($resolvedVariantPrice['discount_amount'] ?? 0), 4),
                        'product_discount_amount' => round((float) ($resolvedVariantPrice['product_discount_amount'] ?? 0), 4),
                        'bulk_discount_amount' => round((float) ($resolvedVariantPrice['bulk_discount_amount'] ?? 0), 4),
                        'coupon_discount_amount' => round((float) ($resolvedVariantPrice['coupon_discount_amount'] ?? 0), 4),
                        'applied_coupon_code' => $resolvedVariantPrice['applied_coupon_code'] ?? null,
                        'min_order_quantity' => (int) ($resolvedVariantPrice['min_order_quantity'] ?? 1),
                        'max_order_quantity' => $resolvedVariantPrice['max_order_quantity'] === null ? null : (int) $resolvedVariantPrice['max_order_quantity'],
                        'lot_size' => (int) ($resolvedVariantPrice['lot_size'] ?? 1),
                        'variant_sku' => $productVariant->sku,
                        'variant_name' => $productVariant->variant_name,
                    ],
                ];
            }

            // Step 3: return all final prepared order item rows.
            return $preparedOrderItems;
        } catch (Throwable $exception) {
            Log::error('Failed to prepare checkout order items.', ['user_id' => $user->id, 'cart_id' => $cart->id, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This calculates final order totals during cart checkout.
    protected function calculateCheckoutTotals(array $validatedCartCheckout, array $preparedOrderItems): array
    {
        try {
            // Step 1: read the optional extra amounts passed during checkout.
            $shippingAmount = round((float) ($validatedCartCheckout['shipping_amount'] ?? 0), 4);
            $adjustmentAmount = round((float) ($validatedCartCheckout['adjustment_amount'] ?? 0), 4);
            $roundingAmount = round((float) ($validatedCartCheckout['rounding_amount'] ?? 0), 4);
            $currency = (string) ($preparedOrderItems[0]['item_snapshot']['currency'] ?? 'INR');

            // Step 2: sum all order item amounts into final order totals.
            $subtotalAmount = round(collect($preparedOrderItems)->sum('subtotal_amount'), 4);
            $taxAmount = round(collect($preparedOrderItems)->sum('tax_amount'), 4);
            $discountAmount = round(collect($preparedOrderItems)->sum('discount_amount'), 4);
            $itemsTotal = round(collect($preparedOrderItems)->sum('total_amount'), 4);
            $totalAmount = round($itemsTotal + $shippingAmount + $adjustmentAmount + $roundingAmount, 4);

            // Step 3: keep a readable pricing snapshot on the order header.
            $pricingSnapshot = [
                'source' => 'cart_checkout',
                'currency' => $currency,
                'items_count' => count($preparedOrderItems),
                'coupon_code' => $validatedCartCheckout['coupon_code'] ?? null,
                'subtotal_amount' => $subtotalAmount,
                'tax_amount' => $taxAmount,
                'discount_amount' => $discountAmount,
                'items_total' => $itemsTotal,
                'shipping_amount' => $shippingAmount,
                'adjustment_amount' => $adjustmentAmount,
                'rounding_amount' => $roundingAmount,
                'total_amount' => $totalAmount,
                'price_types' => collect($preparedOrderItems)
                    ->map(fn (array $preparedOrderItem) => $preparedOrderItem['item_snapshot']['price_type'] ?? null)
                    ->filter()
                    ->unique()
                    ->values()
                    ->all(),
                'pricing_stages' => collect($preparedOrderItems)
                    ->map(fn (array $preparedOrderItem) => $preparedOrderItem['item_snapshot']['pricing_stage'] ?? null)
                    ->filter()
                    ->unique()
                    ->values()
                    ->all(),
            ];

            // Step 4: return the final order totals array.
            return [
                'currency' => $currency,
                'subtotal_amount' => $subtotalAmount,
                'tax_amount' => $taxAmount,
                'discount_amount' => $discountAmount,
                'shipping_amount' => $shippingAmount,
                'adjustment_amount' => $adjustmentAmount,
                'rounding_amount' => $roundingAmount,
                'total_amount' => $totalAmount,
                'pricing_snapshot' => $pricingSnapshot,
            ];
        } catch (Throwable $exception) {
            Log::error('Failed to calculate checkout totals.', ['error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This stores the item-level discount reasons created during checkout for downstream audit and support use.
    protected function storeCheckoutDiscountLines(Order $order, $createdOrderItems, array $preparedOrderItems): void
    {
        // Step 1: stop early when there are no created items to map against the prepared payload.
        if ($createdOrderItems->isEmpty()) {
            return;
        }

        $discountRows = [];

        // Step 2: build one readable discount row per applied pricing rule on each order item.
        foreach ($createdOrderItems as $index => $createdOrderItem) {
            $itemSnapshot = $preparedOrderItems[$index]['item_snapshot'] ?? [];

            if (($itemSnapshot['product_discount_amount'] ?? 0) > 0) {
                $discountRows[] = [
                    'order_id' => $order->id,
                    'order_item_id' => $createdOrderItem->id,
                    'discount_type' => 'product_discount',
                    'discount_code' => null,
                    'discount_name' => 'Product Discount',
                    'discount_rate' => null,
                    'discount_amount' => round((float) $itemSnapshot['product_discount_amount'] * (int) $createdOrderItem->quantity, 4),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            if (($itemSnapshot['bulk_discount_amount'] ?? 0) > 0) {
                $discountRows[] = [
                    'order_id' => $order->id,
                    'order_item_id' => $createdOrderItem->id,
                    'discount_type' => 'bulk',
                    'discount_code' => null,
                    'discount_name' => 'Bulk Price Benefit',
                    'discount_rate' => null,
                    'discount_amount' => round((float) $itemSnapshot['bulk_discount_amount'] * (int) $createdOrderItem->quantity, 4),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            if (($itemSnapshot['coupon_discount_amount'] ?? 0) > 0) {
                $discountRows[] = [
                    'order_id' => $order->id,
                    'order_item_id' => $createdOrderItem->id,
                    'discount_type' => 'coupon',
                    'discount_code' => $itemSnapshot['applied_coupon_code'] ?? null,
                    'discount_name' => 'Coupon Discount',
                    'discount_rate' => null,
                    'discount_amount' => round((float) $itemSnapshot['coupon_discount_amount'] * (int) $createdOrderItem->quantity, 4),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Step 3: insert all discount lines together so checkout keeps one simple write operation.
        if ($discountRows !== []) {
            DB::table('order_discount_lines')->insert($discountRows);
        }
    }
}
