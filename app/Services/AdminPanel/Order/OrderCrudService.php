<?php

namespace App\Services\AdminPanel\Order;

use App\Models\Order\Order;
use Illuminate\Support\Collection;

class OrderCrudService
{
    // Get all orders with basic information for admin list view.
    public function getAllOrdersForAdminList(): Collection
    {
        $allOrders = Order::with(['placedByUser', 'company'])
            ->orderBy('created_at', 'desc')
            ->get();

        $ordersList = [];

        // Prepare each order's data for admin display.
        foreach ($allOrders as $order) {
            $customerName = $order->placedByUser?->name ?? 'Unknown Customer';
            $companyName = $order->company?->name ?? 'N/A';
            $orderStatus = $order->status ?? 'Pending';
            $totalAmount = $order->total_amount ?? 0;

            $orderData = [
                'id' => $order->id,
                'orderNumber' => $order->id,
                'customerName' => $customerName,
                'companyName' => $companyName,
                'status' => $orderStatus,
                'totalAmount' => $totalAmount,
                'createdDate' => $order->created_at,
            ];

            $ordersList[] = $orderData;
        }

        return collect($ordersList);
    }

    // Get order information for viewing and editing.
    public function getOrderForView(int $orderId): ?array
    {
        // Fetch order with related information.
        $order = Order::with(['placedByUser', 'company', 'items', 'shippingAddress'])
            ->find($orderId);

        // Return null if order not found.
        if (!$order) {
            return null;
        }

        // Build order information array.
        $customerName = $order->placedByUser?->name ?? 'Unknown Customer';
        $companyName = $order->company?->name ?? 'N/A';

        // Return order data as array.
        return [
            'id' => $order->id,
            'customerName' => $customerName,
            'companyName' => $companyName,
            'status' => $order->status,
            'currency' => $order->currency,
            'subtotalAmount' => $order->subtotal_amount,
            'taxAmount' => $order->tax_amount,
            'discountAmount' => $order->discount_amount,
            'shippingAmount' => $order->shipping_amount,
            'totalAmount' => $order->total_amount,
            'notes' => $order->notes,
            'createdDate' => $order->created_at,
        ];
    }

    // Update order with provided information.
    public function updateOrder(int $orderId, array $orderData): bool
    {
        // Fetch order record.
        $order = Order::find($orderId);

        // Return false if order not found.
        if (!$order) {
            return false;
        }

        // Prepare updated order information.
        $status = $orderData['status'] ?? $order->status;
        $notes = $orderData['notes'] ?? $order->notes;

        // Update order record.
        $order->update([
            'status' => $status,
            'notes' => $notes,
        ]);

        // Return success status.
        return true;
    }
