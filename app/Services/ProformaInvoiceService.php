<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ProformaInvoiceService
{
    public function __construct(
        protected DataVisibilityService $dataVisibilityService,
    ) {
    }

    /**
     * @return array{
     *     products: Collection,
     *     clientCompanies: Collection,
     *     prefilledProductId: ?int
     * }
     */
    public function createPageData(?User $user, ?int $prefilledProductId = null): array
    {
        $products = $this->dataVisibilityService->visibleProductQuery($user)
            ->orderBy('products.name')
            ->get();

        $clientCompanies = collect();

        if ($user && $user->isB2b()) {
            $companyIds = $this->dataVisibilityService->assignedClientCompanyIds($user);

            if ($user->company_id) {
                $companyIds[] = $user->company_id;
            }

            $clientCompanies = DB::table('companies')
                ->whereIn('id', array_unique($companyIds))
                ->orderBy('name')
                ->get();
        }

        return [
            'products' => $products,
            'clientCompanies' => $clientCompanies,
            'prefilledProductId' => $prefilledProductId,
        ];
    }

    public function findVisibleProduct(?User $user, int $productId): ?object
    {
        return $this->dataVisibilityService->visibleProductQuery($user)
            ->where('products.id', $productId)
            ->first();
    }

    /**
     * @param  array<string, mixed>  $validated
     * @param  array{amount: float, currency: string, price_type: string}  $price
     */
    public function createProformaWithItems(
        array $validated,
        ?User $user,
        object $visibleProduct,
        array $price,
        int $quantity,
        float $lineTotal,
        string $piNumber,
        string $guestSessionId,
    ): void {
        DB::transaction(function () use ($validated, $user, $visibleProduct, $price, $quantity, $lineTotal, $piNumber, $guestSessionId): void {
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
                'guest_session_id' => $user ? null : $guestSessionId,
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
    }

    public function logGuestPiGenerated(string $sessionId, string $path, string $piNumber): void
    {
        DB::table('guest_activity_logs')->insert([
            'session_id' => $sessionId,
            'activity_type' => 'pi_generated',
            'path' => $path,
            'payload' => json_encode(['pi_number' => $piNumber]),
            'created_at' => now(),
        ]);
    }

    public function listVisibleProformas(User $user): LengthAwarePaginator
    {
        return $this->dataVisibilityService->visibleProformaQuery($user)
            ->orderByDesc('pi.created_at')
            ->paginate(15);
    }
}
