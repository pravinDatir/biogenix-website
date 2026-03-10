<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Services\Order\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Throwable;

class OrderController extends Controller
{
    // This renders the order CRUD page with create form, edit form, and the user's order list.
    public function showOrderCrud(Request $request, OrderService $orderService): View
    {
        try {
            // Step 1: load all order page data for the logged-in user.
            return view('order.OrderCrud', $orderService->orderCrudPageData(
                $request->user(),
                $request->integer('edit_order_id'),
            ));
        } catch (Throwable $exception) {
            Log::error('Failed to load order CRUD page.', ['user_id' => $request->user()?->id, 'error' => $exception->getMessage()]);

            // Step 2: return the same page with a simple page-level error message.
            return $this->viewWithError('order.OrderCrud', [
                'products' => collect(),
                'orders' => new LengthAwarePaginator([], 0, 10),
                'editingOrder' => null,
                'editingItems' => collect(),
            ], $exception, 'Unable to load order page.');
        }
    }

    // This validates the submitted form and creates a new order for the logged-in user.
    public function createOrder(Request $request, OrderService $orderService): RedirectResponse
    {
        try {
            // Step 1: validate the submitted order form.
            $validatedOrder = $this->validateOrderRequest($request);

            // Step 2: create the order using the order service.
            $order = $orderService->createOrder($validatedOrder, $request->user());

            // Step 3: return to the CRUD page with a success message.
            return redirect()->route('orders.index')
                ->with('success', 'Order #'.$order->id.' created successfully.');
        } catch (Throwable $exception) {
            Log::error('Failed to create order from controller.', ['user_id' => $request->user()?->id, 'error' => $exception->getMessage()]);

            // Step 4: send validation and data errors back to the same form.
            return $this->redirectBackWithError($exception, 'Unable to create order.');
        }
    }

    // This validates the submitted form and updates one existing order by id.
    public function editOrderById(int $orderId, Request $request, OrderService $orderService): RedirectResponse
    {
        try {
            // Step 1: validate the submitted edit form.
            $validatedOrder = $this->validateOrderRequest($request);

            // Step 2: update the existing order and item rows.
            $order = $orderService->updateOrderById($orderId, $validatedOrder, $request->user());

            // Step 3: redirect to the order detail page after a successful update.
            return redirect()->route('orders.show', $order->id)
                ->with('success', 'Order #'.$order->id.' updated successfully.');
        } catch (Throwable $exception) {
            Log::error('Failed to update order from controller.', ['order_id' => $orderId, 'user_id' => $request->user()?->id, 'error' => $exception->getMessage()]);

            // Step 4: return the user back with a clear error message.
            return $this->redirectBackWithError($exception, 'Unable to update order.');
        }
    }

    // This loads one order by id and shows the order detail page.
    public function getOrderById(int $orderId, Request $request, OrderService $orderService): View
    {
        try {
            // Step 1: load the requested order with relations for the current user.
            $order = $orderService->getOrderById($orderId, $request->user());

            // Step 2: return the order detail blade.
            return view('order.show', [
                'order' => $order,
            ]);
        } catch (Throwable $exception) {
            Log::error('Failed to load order detail page.', ['order_id' => $orderId, 'user_id' => $request->user()?->id, 'error' => $exception->getMessage()]);

            // Step 3: return the detail page with a clean page-level error.
            return $this->viewWithError('order.show', [
                'order' => null,
            ], $exception, 'Unable to load order details.');
        }
    }

    // This softly deletes one order by id for the current logged-in user.
    public function softDeleteOrderById(int $orderId, Request $request, OrderService $orderService): RedirectResponse
    {
        try {
            // Step 1: soft delete the selected order.
            $orderService->softDeleteOrderById($orderId, $request->user());

            // Step 2: redirect to the list page with a success message.
            return redirect()->route('orders.index')
                ->with('success', 'Order deleted successfully.');
        } catch (Throwable $exception) {
            Log::error('Failed to soft delete order from controller.', ['order_id' => $orderId, 'user_id' => $request->user()?->id, 'error' => $exception->getMessage()]);

            // Step 3: return back with a clean user-facing error message.
            return $this->redirectBackWithError($exception, 'Unable to delete order.');
        }
    }

    // This validates the shared order form for both create and update flows.
    protected function validateOrderRequest(Request $request): array
    {
        try {
            // Step 1: validate order header and item array fields.
            return $request->validate([
                'status' => ['required', 'in:draft,submitted,cancelled'],
                'product_id' => ['required', 'array', 'min:1'],
                'product_id.*' => ['nullable', 'integer', 'exists:products,id'],
                'quantity' => ['required', 'array', 'min:1'],
                'quantity.*' => ['nullable', 'integer', 'min:1'],
                'shipping_amount' => ['nullable', 'numeric', 'min:0'],
                'adjustment_amount' => ['nullable', 'numeric'],
                'rounding_amount' => ['nullable', 'numeric'],
                'notes' => ['nullable', 'string', 'max:1000'],
            ]);
        } catch (Throwable $exception) {
            Log::error('Failed to validate order request.', ['user_id' => $request->user()?->id, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }
}
