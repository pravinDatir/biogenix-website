<?php

namespace App\Http\Controllers\Invoice;

use App\Http\Controllers\Controller;
use App\Services\Invoice\QuotationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class QuotationController extends Controller
{
    // This opens the quotation page and provides the visible products needed by the form.
    public function showGenerateQuotePage(Request $request, QuotationService $quotationService): View
    {
        try {
            // Step 1: load the quotation page data with an optional prefilled product id from product details.
            return view('quotation.create', $quotationService->buildGenerateQuotePageData(
                $request->user(),
                $request->integer('product_id'),
            ));
        } catch (Throwable $exception) {
            Log::error('Failed to load quotation page.', ['error' => $exception->getMessage()]);

            return $this->viewWithError('quotation.create', [
                'products' => collect(),
                'clientCompanies' => collect(),
                'prefilledProductId' => $request->integer('product_id'),
                'quotationFlowMode' => 'quote',
            ], $exception, 'Unable to load quotation form.');
        }
    }

    // This saves the quotation and returns the generated PDF download to the user.
    public function createQuotationAndDownloadPdf(Request $request, QuotationService $quotationService): Response|RedirectResponse
    {
        try {
            // Step 1: validate the submitted quotation form before business rules run.
            $validated = $this->validateQuotationPayload($request);

            // Step 2: apply the same recipient scope rules used by the business for self and other-customer quotations.
            $quotationService->guardRecipientScope($validated, $request->user());

            // Step 3: prepare the requested items so product visibility, price, tax, and quantity rules are all captured in one snapshot.
            $preparedItems = $quotationService->prepareQuotationItems($validated, $request->user());
            $quotationTotals = $quotationService->calculateQuotationTotals($preparedItems);

            // Step 4: create the quotation number and save the business record before generating the PDF.
            $quotation = $quotationService->createQuotationWithItems(
                $validated,
                $request->user(),
                $preparedItems,
                $quotationTotals,
                $this->generateQuotationNumber(),
                $request->session()->getId(),
            );

            // Step 5: return the saved quotation as a direct PDF download for the customer.
            return $quotationService->downloadQuotationPdf($quotation);
        } catch (Throwable $exception) {
            Log::error('Failed to create quotation.', ['error' => $exception->getMessage()]);

            return $this->redirectBackWithError($exception, 'Unable to generate quotation.');
        }
    }

    // This validates the quotation request payload in one place so the flow stays easy to maintain.
    protected function validateQuotationPayload(Request $request): array
    {
        try {
            return $request->validate([
                'product_id' => ['required', 'array', 'min:1'],
                'product_id.*' => ['nullable', 'integer', 'exists:products,id'],
                'quantity' => ['required', 'array', 'min:1'],
                'quantity.*' => ['nullable', 'integer', 'min:1'],
                'purpose' => ['required', 'in:self,other'],
                'customer_name' => ['required', 'string', 'max:255'],
                'customer_email' => ['required', 'email', 'max:255'],
                'customer_phone' => ['nullable', 'string', 'max:40'],
                'target_company_id' => ['nullable', 'integer', 'exists:companies,id'],
                'notes' => ['nullable', 'string', 'max:1000'],
            ]);
        } catch (Throwable $exception) {
            Log::error('Failed to validate quotation payload.', ['error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This generates a readable quotation number that can also be used in future mail workflows.
    protected function generateQuotationNumber(): string
    {
        try {
            return 'QT-'.now()->format('YmdHis').'-'.str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);
        } catch (Throwable $exception) {
            Log::error('Failed to generate quotation number.', ['error' => $exception->getMessage()]);
            throw $exception;
        }
    }
}
