<?php

namespace App\Services\Order;

use App\Models\Authorization\User;
use App\Models\Order\Order;
use App\Services\Authorization\DataVisibilityService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Throwable;

class OrderService
{
    public function __construct(
        protected DataVisibilityService $dataVisibilityService,
    ) {
    }

    // This prepares the order CRUD page with products, current orders, and optional edit data.
    public function orderCrudPageData(User $user, ?int $editingOrderId = null): array
    {
        try {
            // Step 1: load all visible products with their resolved order price details.
            $products = $this->loadVisibleProducts($user);

            // Step 2: load existing non-deleted orders for the current user.
            $orders = $this->listOrdersForUser($user);

            // Step 3: load one order for edit when an edit id is passed.
            $editingOrder = null;
            $editingItems = collect();

            if ($editingOrderId) {
                $editingOrder = $this->getOrderById($editingOrderId, $user);
                $editingItems = $editingOrder->items->sortBy('sort_order')->values();
            }

            // Step 4: return all page data to the order CRUD blade.
            return [
                'products' => $products,
                'orders' => $orders,
                'editingOrder' => $editingOrder,
                'editingItems' => $editingItems,
            ];
        } catch (Throwable $exception) {
            Log::error('Failed to build order CRUD page data.', ['user_id' => $user->id, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This creates one order and all of its item rows for the current user.
    public function createOrder(array $validatedOrder, User $user): Order
    {
        try {
            // Step 1: prepare all item rows from the submitted products and quantities.
            $preparedOrderItems = $this->prepareOrderItems($validatedOrder, $user);

            // Step 2: calculate the final order totals from item rows and extra amounts.
            $orderTotals = $this->calculateOrderTotals($validatedOrder, $preparedOrderItems);

            // Step 3: save the order header and order items inside one transaction.
            $order = DB::transaction(function () use ($validatedOrder, $user, $preparedOrderItems, $orderTotals): Order {
                $order = Order::query()->create([
                    'placed_by_user_id' => $user->id,
                    'company_id' => $user->company_id,
                    'status' => $validatedOrder['status'],
                    'currency' => $orderTotals['currency'],
                    'subtotal_amount' => $orderTotals['subtotal_amount'],
                    'tax_amount' => $orderTotals['tax_amount'],
                    'discount_amount' => $orderTotals['discount_amount'],
                    'shipping_amount' => $orderTotals['shipping_amount'],
                    'adjustment_amount' => $orderTotals['adjustment_amount'],
                    'rounding_amount' => $orderTotals['rounding_amount'],
                    'total_amount' => $orderTotals['total_amount'],
                    'pricing_snapshot' => $orderTotals['pricing_snapshot'],
                    'notes' => $validatedOrder['notes'] ?: null,
                    'submitted_at' => $validatedOrder['status'] === 'submitted' ? now() : null,
                    'cancelled_at' => $validatedOrder['status'] === 'cancelled' ? now() : null,
                ]);

                foreach ($preparedOrderItems as $preparedOrderItem) {
                    $order->items()->create($preparedOrderItem);
                }

                return $order;
            });

            // Step 4: log the successful order creation for traceability.
            Log::info('Order created successfully.', ['order_id' => $order->id, 'user_id' => $user->id]);

            // Step 5: return the fully loaded order for downstream use.
            return $this->getOrderById($order->id, $user);
        } catch (Throwable $exception) {
            Log::error('Failed to create order.', ['user_id' => $user->id, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This updates an existing order and replaces its item rows with the submitted snapshot.
    public function updateOrderById(int $orderId, array $validatedOrder, User $user): Order
    {
        try {
            // Step 1: load the existing order that belongs to the current user.
            $order = $this->getOrderById($orderId, $user);

            // Step 2: prepare all fresh item rows from the submitted form data.
            $preparedOrderItems = $this->prepareOrderItems($validatedOrder, $user);

            // Step 3: recalculate the order totals using the latest item rows.
            $orderTotals = $this->calculateOrderTotals($validatedOrder, $preparedOrderItems);

            // Step 4: update the order header and replace old items inside one transaction.
            DB::transaction(function () use ($order, $validatedOrder, $user, $preparedOrderItems, $orderTotals): void {
                $submittedAt = $order->submitted_at;
                $cancelledAt = $order->cancelled_at;

                if ($validatedOrder['status'] === 'submitted' && ! $submittedAt) {
                    $submittedAt = now();
                }

                if ($validatedOrder['status'] === 'cancelled') {
                    $cancelledAt = now();
                }

                if ($validatedOrder['status'] !== 'cancelled') {
                    $cancelledAt = null;
                }

                $order->update([
                    'company_id' => $user->company_id,
                    'status' => $validatedOrder['status'],
                    'currency' => $orderTotals['currency'],
                    'subtotal_amount' => $orderTotals['subtotal_amount'],
                    'tax_amount' => $orderTotals['tax_amount'],
                    'discount_amount' => $orderTotals['discount_amount'],
                    'shipping_amount' => $orderTotals['shipping_amount'],
                    'adjustment_amount' => $orderTotals['adjustment_amount'],
                    'rounding_amount' => $orderTotals['rounding_amount'],
                    'total_amount' => $orderTotals['total_amount'],
                    'pricing_snapshot' => $orderTotals['pricing_snapshot'],
                    'notes' => $validatedOrder['notes'] ?: null,
                    'submitted_at' => $submittedAt,
                    'cancelled_at' => $cancelledAt,
                ]);

                $order->items()->delete();

                foreach ($preparedOrderItems as $preparedOrderItem) {
                    $order->items()->create($preparedOrderItem);
                }
            });

            // Step 5: log the successful order update for review and debugging.
            Log::info('Order updated successfully.', ['order_id' => $orderId, 'user_id' => $user->id]);

            // Step 6: return the latest saved order with relations.
            return $this->getOrderById($orderId, $user);
        } catch (Throwable $exception) {
            Log::error('Failed to update order.', ['order_id' => $orderId, 'user_id' => $user->id, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This returns one visible order with its relations for the current user.
    public function getOrderById(int $orderId, User $user): Order
    {
        try {
            // Step 1: start with the base order query and eager-load the needed relations.
            $query = Order::query()
                ->with([
                    'placedByUser:id,name,email,user_type',
                    'company:id,name,company_type',
                    'items' => fn ($builder) => $builder->orderBy('sort_order')->orderBy('id'),
                    'items.product:id,name,sku',
                    'items.variant:id,product_id,sku,variant_name',
                ]);

            // Step 2: keep the visibility simple by limiting normal users to their own orders.
            if (! $user->isAdmin()) {
                $query->where('placed_by_user_id', $user->id);
            }

            // Step 3: return the order or fail with a clean not-found error.
            return $query->findOrFail($orderId);
        } catch (Throwable $exception) {
            Log::error('Failed to load order by id.', ['order_id' => $orderId, 'user_id' => $user->id, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This softly deletes one visible order so it no longer appears in normal queries.
    public function softDeleteOrderById(int $orderId, User $user): void
    {
        try {
            // Step 1: load the order that the current user is allowed to remove.
            $order = $this->getOrderById($orderId, $user);

            // Step 2: soft delete the order row using Laravel's SoftDeletes support.
            $order->delete();

            // Step 3: log the deletion for audit and support review.
            Log::info('Order soft deleted successfully.', ['order_id' => $orderId, 'user_id' => $user->id]);
        } catch (Throwable $exception) {
            Log::error('Failed to soft delete order.', ['order_id' => $orderId, 'user_id' => $user->id, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This loads visible products and attaches the resolved price fields used by the order form.
    protected function loadVisibleProducts(User $user): Collection
    {
        try {
            // Step 1: load visible products using the existing product visibility rules.
            return $this->dataVisibilityService->visibleProductQuery($user)
                ->orderBy('products.name')
                ->get()
                ->map(function ($product) use ($user) {
                    // Step 2: resolve the current visible price for each product.
                    $price = $this->dataVisibilityService->resolvePrice((int) $product->id, $user);

                    $product->visible_price = $price['amount'] ?? null;
                    $product->visible_currency = $price['currency'] ?? 'INR';
                    $product->visible_price_type = $price['price_type'] ?? null;
                    $product->gst_rate = $price['gst_rate'] ?? 0;
                    $product->tax_amount = $price['tax_amount'] ?? 0;
                    $product->price_after_gst = $price['price_after_gst'] ?? 0;
                    $product->min_order_quantity = $price['min_order_quantity'] ?? 1;
                    $product->max_order_quantity = $price['max_order_quantity'] ?? null;
                    $product->lot_size = $price['lot_size'] ?? 1;
                    $product->product_variant_id = $price['product_variant_id'] ?? null;
                    $product->variant_name = $price['variant_name'] ?? null;
                    $product->variant_sku = $price['variant_sku'] ?? null;

                    return $product;
                });
        } catch (Throwable $exception) {
            Log::error('Failed to load visible products for order.', ['user_id' => $user->id, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This returns paginated orders for the current user so the CRUD page can list them.
    protected function listOrdersForUser(User $user): LengthAwarePaginator
    {
        try {
            // Step 1: start with the order query and eager-load minimal list relations.
            $query = Order::query()
                ->with([
                    'company:id,name',
                ])
                ->orderByDesc('created_at');

            // Step 2: keep normal users limited to their own order list.
            if (! $user->isAdmin()) {
                $query->where('placed_by_user_id', $user->id);
            }

            // Step 3: return the paginated list for the view.
            return $query->paginate(10);
        } catch (Throwable $exception) {
            Log::error('Failed to list orders for user.', ['user_id' => $user->id, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This prepares all order item rows from the submitted product and quantity arrays.
    protected function prepareOrderItems(array $validatedOrder, User $user): array
    {
        try {
            // Step 1: read all submitted product and quantity rows.
            $productIds = is_array($validatedOrder['product_id'] ?? null) ? $validatedOrder['product_id'] : [];
            $quantities = is_array($validatedOrder['quantity'] ?? null) ? $validatedOrder['quantity'] : [];
            $rowCount = max(count($productIds), count($quantities));
            $preparedItems = [];

            // Step 2: prepare one order item per submitted row.
            for ($index = 0; $index < $rowCount; $index++) {
                $productId = (int) ($productIds[$index] ?? 0);
                $quantity = (int) ($quantities[$index] ?? 0);

                if ($productId === 0) {
                    continue;
                }

                $visibleProduct = $this->dataVisibilityService->visibleProductQuery($user)
                    ->where('products.id', $productId)
                    ->first();

                if (! $visibleProduct) {
                    throw ValidationException::withMessages([
                        "product_id.$index" => 'The selected product is not available for this user.',
                    ]);
                }

                $price = $this->dataVisibilityService->resolvePrice($productId, $user);

                if (! $price) {
                    throw ValidationException::withMessages([
                        "product_id.$index" => 'No visible price is configured for the selected product.',
                    ]);
                }

                $this->validateOrderQuantity($quantity, $price, $index);
                $preparedItems[] = $this->buildPreparedOrderItem($visibleProduct, $price, $quantity, $index);
            }

            // Step 3: stop when the user submits an order without valid items.
            if ($preparedItems === []) {
                throw ValidationException::withMessages([
                    'product_id' => 'Add at least one order item.',
                ]);
            }

            return $preparedItems;
        } catch (Throwable $exception) {
            Log::error('Failed to prepare order items.', ['user_id' => $user->id, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This calculates the final order totals from items and extra header amounts.
    protected function calculateOrderTotals(array $validatedOrder, array $preparedOrderItems): array
    {
        try {
            // Step 1: read extra amounts from the submitted form.
            $shippingAmount = round((float) ($validatedOrder['shipping_amount'] ?? 0), 4);
            $adjustmentAmount = round((float) ($validatedOrder['adjustment_amount'] ?? 0), 4);
            $roundingAmount = round((float) ($validatedOrder['rounding_amount'] ?? 0), 4);
            $currency = (string) ($preparedOrderItems[0]['item_snapshot']['currency'] ?? 'INR');

            // Step 2: sum all item-level amounts that will become the order totals.
            $subtotalAmount = round(collect($preparedOrderItems)->sum('subtotal_amount'), 4);
            $taxAmount = round(collect($preparedOrderItems)->sum('tax_amount'), 4);
            $discountAmount = round(collect($preparedOrderItems)->sum('discount_amount'), 4);
            $itemsTotal = round(collect($preparedOrderItems)->sum('total_amount'), 4);
            $totalAmount = round($itemsTotal + $shippingAmount + $adjustmentAmount + $roundingAmount, 4);

            // Step 3: keep an easy-to-read snapshot for debugging and future reviewers.
            $pricingSnapshot = [
                'currency' => $currency,
                'items_count' => count($preparedOrderItems),
                'price_source' => 'current_visible_price',
                'subtotal_amount' => $subtotalAmount,
                'tax_amount' => $taxAmount,
                'discount_amount' => $discountAmount,
                'items_total' => $itemsTotal,
                'shipping_amount' => $shippingAmount,
                'adjustment_amount' => $adjustmentAmount,
                'rounding_amount' => $roundingAmount,
                'total_amount' => $totalAmount,
                'price_types' => collect($preparedOrderItems)
                    ->map(fn (array $item) => $item['item_snapshot']['price_type'] ?? null)
                    ->filter()
                    ->unique()
                    ->values()
                    ->all(),
            ];

            // Step 4: return the final totals array used by create and update flows.
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
            Log::error('Failed to calculate order totals.', ['error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This turns one selected product row into the final order item payload.
    protected function buildPreparedOrderItem(object $visibleProduct, array $price, int $quantity, int $index): array
    {
        try {
            // Step 1: calculate the row totals from the resolved current price.
            $unitPrice = round((float) ($price['amount'] ?? 0), 4);
            $unitTaxAmount = round((float) ($price['tax_amount'] ?? 0), 4);
            $unitPriceAfterGst = round((float) ($price['price_after_gst'] ?? 0), 4);
            $subtotalAmount = round($unitPrice * $quantity, 4);
            $taxAmount = round($unitTaxAmount * $quantity, 4);
            $discountAmount = 0.0000;
            $totalAmount = round($unitPriceAfterGst * $quantity, 4);

            // Step 2: keep the extra pricing fields inside the item snapshot.
            $itemSnapshot = [
                'currency' => $price['currency'] ?? 'INR',
                'price_type' => $price['price_type'] ?? null,
                'gst_rate' => round((float) ($price['gst_rate'] ?? 0), 4),
                'unit_tax_amount' => $unitTaxAmount,
                'unit_price_after_gst' => $unitPriceAfterGst,
                'min_order_quantity' => (int) ($price['min_order_quantity'] ?? 1),
                'max_order_quantity' => $price['max_order_quantity'] === null ? null : (int) $price['max_order_quantity'],
                'lot_size' => (int) ($price['lot_size'] ?? 1),
                'product_variant_id' => $price['product_variant_id'] ?? null,
                'variant_sku' => $price['variant_sku'] ?? null,
                'variant_name' => $price['variant_name'] ?? null,
            ];

            // Step 3: return the final order item payload used by create and update.
            return [
                'product_id' => (int) $visibleProduct->id,
                'product_variant_id' => $price['product_variant_id'] ?? null,
                'sku' => (string) ($price['variant_sku'] ?? $visibleProduct->sku),
                'product_name' => (string) $visibleProduct->name,
                'variant_name' => $price['variant_name'] ?? null,
                'description' => 'Resolved from current visible product price.',
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'subtotal_amount' => $subtotalAmount,
                'discount_amount' => $discountAmount,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
                'sort_order' => $index,
                'item_snapshot' => $itemSnapshot,
            ];
        } catch (Throwable $exception) {
            Log::error('Failed to build order item payload.', ['product_id' => $visibleProduct->id ?? null, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This checks the selected quantity against the current price row rules.
    protected function validateOrderQuantity(int $quantity, array $price, int $index): void
    {
        try {
            // Step 1: read min quantity, max quantity, and lot size from the price row.
            $minOrderQuantity = max(1, (int) ($price['min_order_quantity'] ?? 1));
            $maxOrderQuantity = $price['max_order_quantity'] === null ? null : (int) $price['max_order_quantity'];
            $lotSize = max(1, (int) ($price['lot_size'] ?? 1));

            // Step 2: block quantities below the configured minimum.
            if ($quantity < $minOrderQuantity) {
                throw ValidationException::withMessages([
                    "quantity.$index" => "Quantity for item ".($index + 1)." must be at least {$minOrderQuantity}.",
                ]);
            }

            // Step 3: block quantities above the configured maximum when a maximum exists.
            if ($maxOrderQuantity !== null && $quantity > $maxOrderQuantity) {
                throw ValidationException::withMessages([
                    "quantity.$index" => "Quantity for item ".($index + 1)." must not exceed {$maxOrderQuantity}.",
                ]);
            }

            // Step 4: block quantities that do not match the configured lot size.
            if ($lotSize > 1 && $quantity % $lotSize !== 0) {
                throw ValidationException::withMessages([
                    "quantity.$index" => "Quantity for item ".($index + 1)." must be in multiples of {$lotSize}.",
                ]);
            }
        } catch (Throwable $exception) {
            Log::error('Failed to validate order quantity.', ['item_index' => $index, 'quantity' => $quantity, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

}
