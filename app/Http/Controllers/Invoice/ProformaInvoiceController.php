<?php

namespace App\Http\Controllers\Invoice;

use App\Http\Controllers\Controller;
use App\Models\Authorization\User;
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
    // This renders the instant quotation page with visible products.
    public function create(Request $request, ProformaInvoiceService $proformaInvoiceService): View
    {
        try {
            // Step 1: load the quotation page data with an optional prefilled product id.
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

    // This renders the PI request page so users can ask the team to issue a reviewed PI later.
    public function showPiQuotationRequestPage(Request $request, ProformaInvoiceService $proformaInvoiceService): View
    {
        try {
            // Step 1: load the same visible product data so the request page follows the current pricing and product visibility rules.
            return view('pi-quotation.generate', array_merge(
                $proformaInvoiceService->createPageData(
                    $request->user(),
                    $request->integer('product_id'),
                ),
                [
                    'quotationFlowMode' => 'pi_request',
                ],
            ));
        } catch (Throwable $exception) {
            Log::error('Failed to load PI request page.', ['error' => $exception->getMessage()]);

            return $this->viewWithError('pi-quotation.generate', [
                'products' => collect(),
                'clientCompanies' => collect(),
                'prefilledProductId' => $request->integer('product_id'),
                'quotationFlowMode' => 'pi_request',
            ], $exception, 'Unable to load PI request form.');
        }
    }

    // This creates an instant quotation record and returns the branded PDF immediately.
    public function store(Request $request, DataVisibilityService $dataVisibilityService, ProformaInvoiceService $proformaInvoiceService): Response|RedirectResponse
    {
        try {
            // Step 1: validate the submitted request payload before any business checks run.
            $validated = $this->validatePiPayload($request);

            // Step 2: confirm the user is allowed to create the requested recipient scope.
            $user = $request->user();
            $this->authorizePiTargetSelection($validated, $user, $dataVisibilityService);

            // Step 3: prepare the visible items and calculate totals from the shared pricing engine.
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

    // This stores a PI generation request so the team can review quantities, price, tax, stock, and delivery terms before issuing the final PI.
    public function submitPiQuotationRequest(Request $request, DataVisibilityService $dataVisibilityService, ProformaInvoiceService $proformaInvoiceService): RedirectResponse
    {
        try {
            // Step 1: validate the submitted request payload before any business checks run.
            $validated = $this->validatePiPayload($request);

            // Step 2: confirm the user is allowed to create the requested recipient scope.
            $user = $request->user();
            $this->authorizePiTargetSelection($validated, $user, $dataVisibilityService);

            // Step 3: prepare the visible items and calculate totals so the internal team receives a complete pricing snapshot.
            $preparedItems = $proformaInvoiceService->prepareProformaItems($validated, $user);
            $invoiceTotals = $proformaInvoiceService->calculateInvoiceTotals($preparedItems);
            $requestReference = $this->generatePiRequestReference();

            // Step 4: save the request in pending review status instead of issuing the final PI immediately.
            $proformaInvoiceService->createPendingPiRequestWithItems(
                $validated,
                $user,
                $preparedItems,
                $invoiceTotals,
                $requestReference,
                $request->session()->getId(),
            );

            // Step 5: keep a simple activity record so the business team can trace who submitted the request.
            $proformaInvoiceService->logPiRequestSubmitted(
                $user,
                $request->session()->getId(),
                $request->path(),
                $requestReference,
            );

            return redirect()->route('pi-quotation.generate')
                ->with('status', "PI request {$requestReference} submitted successfully. Our team will review it and issue the final PI after verification.");
        } catch (Throwable $exception) {
            Log::error('Failed to submit PI request.', ['error' => $exception->getMessage()]);

            return $this->redirectBackWithError($exception, 'Unable to submit PI request.');
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

            // Step 2: allow download only after the request has moved past internal review.
            if (! $proformaInvoiceService->isReadyForPdfDownload($proforma)) {
                return redirect()->route('proforma.index')
                    ->with('status', 'This PI request is still under internal review. PDF download will be available after the final PI is issued.');
            }

            // Step 3: return the invoice PDF download response.
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

    // This validates the shared PI and quotation payload so both flows follow the same business input rules.
    protected function validatePiPayload(Request $request): array
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
            Log::error('Failed to validate PI payload.', ['error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This applies the same recipient and company visibility checks for both instant quotes and PI requests.
    protected function authorizePiTargetSelection(array $validated, ?User $user, DataVisibilityService $dataVisibilityService): void
    {
        try {
            // Step 1: stop users from requesting other-customer documents when their account scope does not allow it.
            if ($validated['purpose'] === 'other' && ! $dataVisibilityService->canGeneratePiForOther($user)) {
                abort(403, 'You are not allowed to generate a PI for another customer.');
            }

            // Step 2: keep B2C accounts limited to self-flow requests only.
            if ($user && $user->isB2c() && $validated['purpose'] !== 'self') {
                abort(403, 'B2C users can only generate PI for self.');
            }

            // Step 3: when a B2B user selects another company, confirm that company is within their allowed visibility scope.
            if ($user && $user->isB2b() && $validated['purpose'] === 'other') {
                $targetCompanyId = isset($validated['target_company_id']) ? (int) $validated['target_company_id'] : null;

                if ($targetCompanyId && ! $dataVisibilityService->canAccessCompanyData($user, $targetCompanyId)) {
                    abort(403, 'You can only generate PI for your own company or assigned clients.');
                }
            }
        } catch (Throwable $exception) {
            Log::error('Failed to authorize PI target selection.', ['user_id' => $user?->id, 'error' => $exception->getMessage()]);
            throw $exception;
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

    // This generates a readable request reference that can be shared with the customer before the final PI is issued.
    protected function generatePiRequestReference(): string
    {
        try {
            return 'PI-REQ-'.now()->format('YmdHis').'-'.str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);
        } catch (Throwable $exception) {
            Log::error('Failed to generate PI request reference.', ['error' => $exception->getMessage()]);
            throw $exception;
        }
    }
}
