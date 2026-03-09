<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

class RegisterResponse implements RegisterResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     */
    public function toResponse($request)
    {
        if ($request instanceof Request && $request->user()?->status === 'pending_approval') {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->with('status', 'Registration submitted. Your B2B account is pending admin approval.');
        }

        return $request->wantsJson()
            ? new JsonResponse('', 201)
            : redirect()->intended(config('fortify.home'));
    }
}
