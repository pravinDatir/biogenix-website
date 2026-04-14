<?php

namespace App\Services\AdminPanel\Proforma;

use App\Models\Proforma\ProformaInvoice;
use Illuminate\Support\Collection;

class ProformaCrudService
{
    // Get all proforma invoices with basic information for admin list view.
    public function getAllProformasForAdminList(): Collection
    {
        $allProformas = ProformaInvoice::with(['creator', 'ownerUser', 'ownerCompany'])
            ->orderBy('created_at', 'desc')
            ->get();

        $proformasList = [];

        // Prepare each proforma's data for admin display.
        foreach ($allProformas as $proforma) {
            $creatorName = $proforma->creator?->name ?? 'Unknown Creator';
            $targetName = $proforma->target_name ?? 'N/A';
            $status = $proforma->status ?? 'Pending';
            $totalAmount = $proforma->total_amount ?? 0;

            $proformaData = [
                'id' => $proforma->id,
                'piNumber' => $proforma->pi_number,
                'creatorName' => $creatorName,
                'targetName' => $targetName,
                'status' => $status,
                'totalAmount' => $totalAmount,
                'createdDate' => $proforma->created_at,
            ];

            $proformasList[] = $proformaData;
        }

        return collect($proformasList);
    }

    // Get proforma information for viewing and editing.
    public function getProformaForView(int $proformaId): ?array
    {
        // Fetch proforma with related information.
        $proforma = ProformaInvoice::with(['creator', 'ownerUser', 'ownerCompany', 'items'])
            ->find($proformaId);

        // Return null if proforma not found.
        if (!$proforma) {
            return null;
        }

        // Build proforma information array.
        $creatorName = $proforma->creator?->name ?? 'Unknown Creator';
        $ownerName = $proforma->ownerUser?->name ?? 'N/A';

        // Return proforma data as array.
        return [
            'id' => $proforma->id,
            'piNumber' => $proforma->pi_number,
            'creatorName' => $creatorName,
            'ownerName' => $ownerName,
            'targetName' => $proforma->target_name,
            'targetEmail' => $proforma->target_email,
            'targetPhone' => $proforma->target_phone,
            'status' => $proforma->status,
            'currency' => $proforma->currency,
            'subtotal' => $proforma->subtotal,
            'taxAmount' => $proforma->tax_amount,
            'discountAmount' => $proforma->discount_amount,
            'totalAmount' => $proforma->total_amount,
            'notes' => $proforma->notes,
            'createdDate' => $proforma->created_at,
        ];
    }

    // Create new proforma invoice with provided information.
    public function createProforma(array $proformaData): int
    {
        // Prepare proforma information.
        $targetName = $proformaData['target_name'] ?? null;
        $targetEmail = $proformaData['target_email'] ?? null;
        $targetPhone = $proformaData['target_phone'] ?? null;
        $status = $proformaData['status'] ?? 'Draft';
        $currency = $proformaData['currency'] ?? 'USD';
        $notes = $proformaData['notes'] ?? null;

        // Create new proforma record.
        $newProforma = ProformaInvoice::create([
            'target_name' => $targetName,
            'target_email' => $targetEmail,
            'target_phone' => $targetPhone,
            'status' => $status,
            'currency' => $currency,
            'notes' => $notes,
            'subtotal' => 0,
            'tax_amount' => 0,
            'discount_amount' => 0,
            'price_after_gst' => 0,
            'total_amount' => 0,
        ]);

        // Return proforma ID.
        return $newProforma->id;
    }

    // Update proforma with provided information.
    public function updateProforma(int $proformaId, array $proformaData): bool
    {
        // Fetch proforma record.
        $proforma = ProformaInvoice::find($proformaId);

        // Return false if proforma not found.
        if (!$proforma) {
            return false;
        }

        // Prepare updated proforma information.
        $targetName = $proformaData['target_name'] ?? $proforma->target_name;
        $targetEmail = $proformaData['target_email'] ?? $proforma->target_email;
        $targetPhone = $proformaData['target_phone'] ?? $proforma->target_phone;
        $status = $proformaData['status'] ?? $proforma->status;
        $notes = $proformaData['notes'] ?? $proforma->notes;

        // Update proforma record.
        $proforma->update([
            'target_name' => $targetName,
            'target_email' => $targetEmail,
            'target_phone' => $targetPhone,
            'status' => $status,
            'notes' => $notes,
        ]);

        // Return success status.
        return true;
    }
}
