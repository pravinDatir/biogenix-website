<?php
namespace App\Services\Utility;

class QuantityValidator
{
    // Check if quantity is valid for the order.
    public function isValid(int $quantity, array $priceData): bool
    {
        // Get minimum order quantity required.
        $minQuantity = (int) ($priceData['min_order_quantity'] ?? 1);

        // Get maximum order quantity allowed.
        $maxQuantity = $priceData['max_order_quantity'] ?? null;

        // Get lot size (must be multiple of this).
        $lotSize = (int) ($priceData['lot_size'] ?? 1);

        // Check if quantity meets minimum.
        if ($quantity < $minQuantity) {
            return false;
        }

        // Check if quantity exceeds maximum (if set).
        if ($maxQuantity !== null && $quantity > $maxQuantity) {
            return false;
        }

        // Check if quantity is multiple of lot size.
        if ($lotSize > 1 && $quantity % $lotSize !== 0) {
            return false;
        }

        return true;
    }

    // Get error message for invalid quantity.
    public function getErrorMessage(int $quantity, array $priceData): string
    {
        // Get quantity constraints.
        $minQuantity = (int) ($priceData['min_order_quantity'] ?? 1);
        $maxQuantity = $priceData['max_order_quantity'] ?? null;
        $lotSize = (int) ($priceData['lot_size'] ?? 1);

        // Check minimum quantity failure.
        if ($quantity < $minQuantity) {
            return "Minimum order quantity is {$minQuantity}";
        }

        // Check maximum quantity failure.
        if ($maxQuantity !== null && $quantity > $maxQuantity) {
            return "Maximum order quantity is {$maxQuantity}";
        }

        // Check lot size failure.
        if ($lotSize > 1 && $quantity % $lotSize !== 0) {
            return "Quantity must be in multiples of {$lotSize}";
        }

        return "Quantity is invalid";
    }
}
