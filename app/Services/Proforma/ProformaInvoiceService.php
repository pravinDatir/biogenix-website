<?php

namespace App\Services\Proforma;

use App\Models\Authorization\User;
use App\Services\Invoice\InvoiceService;

class ProformaInvoiceService
{
    public function __construct(
        protected InvoiceService $invoiceService,
    ) {
    }

    // Load page data for proforma request form.
    public function getRequestPageData(?User $user, ?int $prefilledProductId = null): array
    {
        return $this->invoiceService->loadFormPageData($user, 'proforma', $prefilledProductId);
    }

    // Prepare proforma items from submitted form data.
    public function prepareItems(array $requestData, ?User $user): array
    {
        return $this->invoiceService->prepareInvoiceItems($requestData, $user);
    }

    // Calculate totals from prepared items.
    public function calculateTotals(array $preparedItems): array
    {
        return $this->invoiceService->calculateInvoiceTotals($preparedItems);
    }

    // Save proforma invoice request with items.
    public function saveRequestedProforma(
        array $requestData,
        ?User $user,
        array $preparedItems,
        array $requestTotals,
        string $requestNumber,
        string $guestSessionId,
    ): void {
        $this->invoiceService->saveProformaInvoice(
            $requestData,
            $user,
            $preparedItems,
            $requestTotals,
            $requestNumber,
            $guestSessionId,
        );
    }

    // Log activity for submitted proforma request.
    public function recordRequestSubmitted(?User $user, string $sessionId, string $path, string $requestNumber): void
    {
        $this->invoiceService->logProformaSubmission($user, $sessionId, $path, $requestNumber);
    }
}
