<?php

namespace App\Http\Controllers;

use App\Services\DataVisibilityService;
use App\Services\ProformaInvoiceService;
use App\Services\RolePermissionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProformaInvoiceController extends Controller
{
    public function create(Request $request, ProformaInvoiceService $proformaInvoiceService): View
    {
        return view('proforma.create', $proformaInvoiceService->createPageData(
            $request->user(),
            $request->integer('product_id'),
        ));
    }

    public function store(
        Request $request,
        DataVisibilityService $dataVisibilityService,
        ProformaInvoiceService $proformaInvoiceService,
        RolePermissionService $rolePermissionService,
    ): RedirectResponse {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'purpose' => ['required', 'in:self,other'],
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email', 'max:255'],
            'customer_phone' => ['nullable', 'string', 'max:40'],
            'target_company_id' => ['nullable', 'integer', 'exists:companies,id'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $user = $request->user();

        $visibleProduct = $proformaInvoiceService->findVisibleProduct($user, (int) $validated['product_id']);

        abort_if(! $visibleProduct, 403, 'The selected product is outside your visibility scope.');

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

        if ($user && ! $rolePermissionService->hasPermission($user, 'pi.generate.self')) {
            abort(403, 'You are not allowed to generate PI.');
        }

        $price = $dataVisibilityService->resolvePrice((int) $validated['product_id'], $user);
        abort_if(! $price, 403, 'No visible price is configured for this product.');

        $quantity = (int) $validated['quantity'];
        $lineTotal = round($price['amount'] * $quantity, 2);
        $piNumber = $this->generatePiNumber();

        $proformaInvoiceService->createProformaWithItems(
            $validated,
            $user,
            $visibleProduct,
            $price,
            $quantity,
            $lineTotal,
            $piNumber,
            $request->session()->getId(),
        );

        if (! $user) {
            $proformaInvoiceService->logGuestPiGenerated(
                $request->session()->getId(),
                $request->path(),
                $piNumber,
            );
        }

        if ($user) {
            return redirect()
                ->route('proforma.index')
                ->with('status', "PI {$piNumber} generated successfully.");
        }

        return redirect()
            ->route('proforma.create')
            ->with('status', "Guest PI {$piNumber} generated successfully.");
    }

    public function index(Request $request, ProformaInvoiceService $proformaInvoiceService): View
    {
        return view('proforma.index', [
            'proformas' => $proformaInvoiceService->listVisibleProformas($request->user()),
        ]);
    }

    protected function generatePiNumber(): string
    {
        return 'PI-'.now()->format('YmdHis').'-'.str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);
    }
}
