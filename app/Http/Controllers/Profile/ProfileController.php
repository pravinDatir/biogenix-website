<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Services\Profile\ProfileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ProfileController extends Controller
{
    // This renders the live profile page for the signed-in customer.
    public function showMyProfilePage(Request $request, ProfileService $profileService): View
    {
        try {
            // Step 1: ask the profile service for the current customer profile data used by the existing page UI.
            $pageData = $profileService->buildMyProfilePageData($request->user());

            // Step 2: render the existing profile page with real backend data.
            return view('customer.profile', $pageData);
        } catch (Throwable $exception) {
            Log::error('Failed to load customer profile page.', [
                'user_id' => $request->user()?->id,
                'error' => $exception->getMessage(),
            ]);

            return $this->viewWithError('customer.profile', [
                'portal' => 'b2c',
                'profileUser' => $request->user(),
                'profileCompany' => null,
                'profileAddress' => null,
                'profileSummary' => [
                    'orders_count' => 0,
                    'tickets_count' => 0,
                    'status_label' => 'Unknown',
                ],
            ], $exception, 'Unable to load profile right now.');
        }
    }

    // This saves the editable profile details shown on the current profile page.
    public function updateMyProfileSection(Request $request, ProfileService $profileService): RedirectResponse
    {
        try {
            $user = $request->user();

            // Step 1: validate the submitted profile fields using rules prepared for the current customer type.
            $validated = $request->validate($profileService->profileValidationRules($user));

            // Step 2: save the approved profile changes through one shared business service.
            $profileService->saveMyProfileSection($user, $validated);

            return redirect()
                ->route('customer.profile.preview')
                ->with('status', 'Profile updated successfully.');
        } catch (Throwable $exception) {
            Log::error('Failed to update customer profile.', [
                'user_id' => $request->user()?->id,
                'error' => $exception->getMessage(),
            ]);

            return $this->redirectBackWithError($exception, 'Unable to update profile.');
        }
    }

}
