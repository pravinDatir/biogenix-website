<?php

namespace App\Http\Controllers;

use App\Services\DataVisibilityService;
use App\Services\RolePermissionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProformaInvoiceController extends Controller
{
    public function create(Request $request, DataVisibilityService $dataVisibilityService): View
    {
        $products = $dataVisibilityService->visibleProductQuery($request->user())
            ->orderBy('products.name')
            ->get();

        $clientCompanies = collect();
        $user = $request->user();

        if ($user && $user->isB2b()) {
            $companyIds = $dataVisibilityService->assignedClientCompanyIds($user);

            if ($user->company_id) {
                $companyIds[] = $user->company_id;
            }

            $clientCompanies = DB::table('companies')
                ->whereIn('id', array_unique($companyIds))
                ->orderBy('name')
                ->get();
        }

        return view('proforma.create', [
            'products' => $products,
            'clientCompanies' => $clientCompanies,
            'prefilledProductId' => $request->integer('product_id'),
        ]);
    }

    public function store(
        Request $request,
        DataVisibilityService $dataVisibilityService,
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

        $visibleProduct = $dataVisibilityService->visibleProductQuery($user)
            ->where('products.id', (int) $validated['product_id'])
            ->first();

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

        DB::transaction(function () use ($validated, $user, $quantity, $lineTotal, $price, $visibleProduct, $request, $piNumber): void {
            $piId = DB::table('proforma_invoices')->insertGetId([
                'pi_number' => $piNumber,
                'requester_type' => $user ? 'user' : 'guest',
                'created_by_user_id' => $user?->id,
                'owner_user_id' => $user?->id,
                'owner_company_id' => $user?->company_id,
                'target_type' => $validated['purpose'],
                'target_name' => $validated['customer_name'],
                'target_email' => $validated['customer_email'],
                'target_phone' => $validated['customer_phone'] ?: null,
                'target_company_id' => isset($validated['target_company_id']) ? (int) $validated['target_company_id'] : null,
                'status' => 'draft',
                'subtotal' => $lineTotal,
                'total_amount' => $lineTotal,
                'guest_session_id' => $user ? null : $request->session()->getId(),
                'notes' => $validated['notes'] ?: null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('proforma_invoice_items')->insert([
                'proforma_invoice_id' => $piId,
                'product_id' => (int) $validated['product_id'],
                'product_name' => $visibleProduct->name,
                'sku' => $visibleProduct->sku,
                'quantity' => $quantity,
                'unit_price' => $price['amount'],
                'line_total' => $lineTotal,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });

        if (! $user) {
            DB::table('guest_activity_logs')->insert([
                'session_id' => $request->session()->getId(),
                'activity_type' => 'pi_generated',
                'path' => $request->path(),
                'payload' => json_encode(['pi_number' => $piNumber]),
                'created_at' => now(),
            ]);
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

    public function index(Request $request, DataVisibilityService $dataVisibilityService): View
    {
        return view('proforma.index', [
            'proformas' => $dataVisibilityService->visibleProformaQuery($request->user())
                ->orderByDesc('pi.created_at')
                ->paginate(15),
        ]);
    }

    protected function generatePiNumber(): string
    {
        return 'PI-'.now()->format('YmdHis').'-'.str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);
    }
}
