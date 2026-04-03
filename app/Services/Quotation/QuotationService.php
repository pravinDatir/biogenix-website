<?php

namespace App\Services\Quotation;

use App\Models\Authorization\User;
use App\Models\Quotation\Quotation;
use App\Services\Invoice\InvoiceService;
use Symfony\Component\HttpFoundation\Response;

class QuotationService
{
    public function __construct(  protected InvoiceService $invoiceService,) {
    }

    // Load create page with products and companies.
    public function getCreatePageData(?User $user, ?int $prefilledProductId = null): array
    {
        return $this->invoiceService->loadFormPageData($user, 'quotation', $prefilledProductId);
    }

    // Prepare quotation items from submitted form data.
    public function prepareItems(array $quotationData, ?User $user): array
    {
        return $this->invoiceService->prepareInvoiceItems($quotationData, $user);
    }

    // Calculate totals from prepared items.
    public function calculateTotals(array $preparedItems): array
    {
        return $this->invoiceService->calculateInvoiceTotals($preparedItems);
    }

    // Save quotation with items.
    public function saveQuotation(
        array $quotationData,
        ?User $user,
        array $preparedItems,
        array $quotationTotals,
        string $quotationNumber,
        string $guestSessionId,
    ): Quotation {
        return $this->invoiceService->saveQuotation(
            $quotationData,
            $user,
            $preparedItems,
            $quotationTotals,
            $quotationNumber,
            $guestSessionId,
        );
    }

    // Download quotation as PDF.
    public function downloadPdf(Quotation $quotation): Response
    {
        return $this->invoiceService->downloadQuotationPdf($quotation);
    }
}
