<?php

namespace App\Http\Controllers\Invoice;

use App\Http\Controllers\Controller;
use App\Services\Authorization\DataVisibilityService;
use App\Services\Invoice\ProformaInvoiceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ProformaInvoiceController extends Controller
{
    // This renders the PI create page with visible products.
    public function create(Request $request, ProformaInvoiceService $proformaInvoiceService): View
    {
        try {
            // Step 1: load PI create-page data with an optional prefilled product id.
            return view('invoice.create', $proformaInvoiceService->createPageData(
                $request->user(),
                $request->integer('product_id'),
            ));
        } catch (Throwable $exception) {
            Log::error('Failed to load PI create page.', ['error' => $exception->getMessage()]);

            return $this->viewWithError('invoice.create', [
                'products' => collect(),
                'clientCompanies' => collect(),
                'prefilledProductId' => $request->integer('product_id'),
            ], $exception, 'Unable to load PI form.');
        }
    }

    // This creates a proforma invoice from the submitted form.
    public function store(
        Request $request,
        DataVisibilityService $dataVisibilityService,
        ProformaInvoiceService $proformaInvoiceService,
    ): Response|RedirectResponse {
        try {
            // Step 1: validate the PI form with multiple item rows.
            $validated = $request->validate([
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

            // Step 2: enforce purpose and company-access rules.
            $user = $request->user();

            if ($validated['purpose'] === 'other' && ! $dataVisibilityService->canGeneratePiForOther($user)) {
                abort(403, 'You are not allowed to generate a PI for another customer.');
            }

            if ($user && $user->isB2c() && $validated['purpose'] !== 'self') {
                abort(403, 'B2C users can only generate PI for self.');
            }

            if ($user && $user->isB2b() && $validated['purpose'] === 'other') {
                $targetCompanyId = isset($validated['target_company_id']) ? (int) $validated['target_company_id'] : null;

                if ($targetCompanyId && ! $dataVisibilityService->canAccessCompanyData($user, $targetCompanyId)) {
                    abort(403, 'You can only generate PI for your own company or assigned clients.');
                }
            }

            // Step 3: prepare all PI items and calculate the invoice totals.
            $preparedItems = $proformaInvoiceService->prepareProformaItems($validated, $user);
            $invoiceTotals = $proformaInvoiceService->calculateInvoiceTotals($preparedItems);
            $piNumber = $this->generatePiNumber();

            $proforma = $proformaInvoiceService->createProformaWithItems(
                $validated,
                $user,
                $preparedItems,
                $invoiceTotals,
                $piNumber,
                $request->session()->getId(),
            );

            // Step 4: store PI activity for guest and logged-in users.
            $proformaInvoiceService->logPiGenerated(
                $user,
                $request->session()->getId(),
                $request->path(),
                $piNumber,
            );

            // Step 5: return the generated invoice as a file download.
            return $proformaInvoiceService->downloadProformaPdf($proforma);
        } catch (Throwable $exception) {
            Log::error('Failed to store PI.', ['error' => $exception->getMessage()]);

            return $this->redirectBackWithError($exception, 'Unable to generate PI.');
        }
    }

    // This downloads an existing visible PI as a PDF file.
    public function download(int $proformaId, Request $request, ProformaInvoiceService $proformaInvoiceService): Response|RedirectResponse
    {
        try {
            // Step 1: allow only visible PI records for the logged-in user.
            $proforma = $proformaInvoiceService->findVisibleProforma($request->user(), $proformaId);
            Log::info('Invoice Proforma download found proforma for download.', ['proforma_id' => $proformaId, 'user_id' => $request->user()->id]);

            abort_if(! $proforma, 404);

            // Step 2: return the invoice PDF download response.
            return $proformaInvoiceService->downloadProformaPdf($proforma);
        } catch (Throwable $exception) {
            Log::error('Failed to download PI.', ['proforma_id' => $proformaId, 'error' => $exception->getMessage()]);

            return $this->redirectBackWithError($exception, 'Unable to download invoice PDF.');
        }
    }

    // This renders the PI listing page.
    public function index(Request $request, ProformaInvoiceService $proformaInvoiceService): View
    {
        try {
            // Step 1: load visible PIs for the current user.
            return view('invoice.index', [
                'proformas' => $proformaInvoiceService->listVisibleProformas($request->user()),
            ]);
        } catch (Throwable $exception) {
            Log::error('Failed to load PI index.', ['error' => $exception->getMessage()]);

            return $this->viewWithError('invoice.index', [
                'proformas' => new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15),
            ], $exception, 'Unable to load proformas.');
        }
    }

    // This generates a simple unique PI number.
    protected function generatePiNumber(): string
    {
        try {
            return 'PI-'.now()->format('YmdHis').'-'.str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);
        } catch (Throwable $exception) {
            Log::error('Failed to generate PI number.', ['error' => $exception->getMessage()]);
            throw $exception;
        }
    }
}
