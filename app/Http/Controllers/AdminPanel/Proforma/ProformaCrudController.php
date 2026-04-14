<?php

namespace App\Http\Controllers\AdminPanel\Proforma;

use App\Http\Controllers\Controller;
use App\Services\AdminPanel\Proforma\ProformaCrudService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class ProformaCrudController extends Controller
{
    public function __construct(protected ProformaCrudService $proformaCrudService)
    {
    }

    // Display proforma list for admin panel.
    public function index(): View
    {
        try {
            // Fetch all proformas with basic information.
            $proformas = $this->proformaCrudService->getAllProformasForAdminList();

            // Return view with proformas data.
            return view('admin.pi-quotation', [
                'proformas' => $proformas,
            ]);
        } catch (Throwable $exception) {
            // Return view with empty proformas if error occurs.
            $proformas = collect([]);

            return view('admin.pi-quotation', [
                'proformas' => $proformas,
            ]);
        }
    }

    // Display create proforma form.
    public function create(): View
    {
        try {
            // Return view for creating proforma.
            return view('admin.pi-quotation-create', []);
        } catch (Throwable $exception) {
            // Return view if error occurs.
            return view('admin.pi-quotation-create', []);
        }
    }

    // Store new proforma from form submission.
    public function store(Request $request): RedirectResponse
    {
        try {
            // Validate required proforma information.
            $validated = $request->validate([
                'target_name' => 'required|string|max:255',
                'target_email' => 'nullable|email|max:255',
                'target_phone' => 'nullable|string|max:20',
                'status' => 'required|string|max:50',
                'currency' => 'nullable|string|max:10',
                'notes' => 'nullable|string',
            ]);

            // Create new proforma record in database.
            $proformaId = $this->proformaCrudService->createProforma($validated);

            // Redirect to proforma list with success message.
            return redirect()->route('admin.pi-quotation.index')
                ->with('success', 'Proforma created successfully.');
        } catch (Throwable $exception) {
            // Redirect back to form with error message.
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create proforma. Please try again.');
        }
    }

    // Display proforma details for viewing and editing.
    public function show(int $proformaId): View
    {
        try {
            // Fetch proforma information for viewing.
            $proforma = $this->proformaCrudService->getProformaForView($proformaId);

            // Abort if proforma not found.
            if (!$proforma) {
                abort(404);
            }

            // Return view with proforma data.
            return view('admin.pi-quotation-edit', [
                'proforma' => $proforma,
            ]);
        } catch (Throwable $exception) {
            // Abort with error if proforma cannot be fetched.
            abort(500);
        }
    }

    // Update proforma from form submission.
    public function update(Request $request, int $proformaId): RedirectResponse
    {
        try {
            // Validate proforma information to update.
            $validated = $request->validate([
                'target_name' => 'required|string|max:255',
                'target_email' => 'nullable|email|max:255',
                'target_phone' => 'nullable|string|max:20',
                'status' => 'required|string|max:50',
                'notes' => 'nullable|string',
            ]);

            // Update proforma record in database.
            $isUpdated = $this->proformaCrudService->updateProforma($proformaId, $validated);

            // Check if update was successful.
            if (!$isUpdated) {
                return redirect()->back()
                    ->with('error', 'Proforma not found.');
            }

            // Redirect to proforma list with success message.
            return redirect()->route('admin.pi-quotation.index')
                ->with('success', 'Proforma updated successfully.');
        } catch (Throwable $exception) {
            // Redirect back to form with error message.
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update proforma. Please try again.');
        }
    }
}
