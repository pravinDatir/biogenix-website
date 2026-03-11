<div class="min-h-screen bg-slate-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8 bg-gradient-to-br from-slate-50 to-blue-50/50">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="text-center mb-8">
            <a href="/" class="inline-flex items-center justify-center h-12 w-12 rounded-xl bg-blue-600 shadow-lg text-white mb-6">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
            </a>
            <h1 class="text-3xl font-bold tracking-tight text-slate-900">Set New Password</h1>
            <p class="mt-2 text-sm text-slate-600">Create a strong password for your account.</p>
        </div>

        <div class="bg-white py-8 px-4 shadow-xl sm:rounded-2xl sm:px-10 border border-slate-100">
            <form id="resetPasswordForm" method="POST" action="{{ route('password.update') }}" class="space-y-5" novalidate>
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">Email Address</label>
                    <input 
                        id="email" 
                        name="email" 
                        type="email" 
                        class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-slate-900 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 hover:border-blue-400 @error('email') border-red-500 ring-2 ring-red-200 @enderror" 
                        value="{{ old('email', $request->email) }}" 
                        required
                    >
                    @error('email')
                        <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                    <span class="error"></span>
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-700 mb-2">New Password</label>
                    <input 
                        id="password" 
                        name="password" 
                        type="password" 
                        class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-slate-900 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 hover:border-blue-400 @error('password') border-red-500 ring-2 ring-red-200 @enderror" 
                        placeholder="Min. 8 characters"
                        required
                    >
                    @error('password')
                        <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                    <span class="error"></span>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-2">Confirm Password</label>
                    <input 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        type="password" 
                        class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-slate-900 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 hover:border-blue-400 @error('password_confirmation') border-red-500 ring-2 ring-red-200 @enderror" 
                        placeholder="Re-enter password"
                        required
                    >
                    @error('password_confirmation')
                        <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                    <span class="error"></span>
                </div>

                <div class="pt-2">
                    <button id="resetPasswordSubmitBtn" class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-blue-500/30 hover:shadow-blue-500/40 transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" type="submit">
                        Reset Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('resetPasswordForm');
        const submitBtn = document.getElementById('resetPasswordSubmitBtn');
        if (!form || !submitBtn) return;

        form.addEventListener('submit', function () {
            submitBtn.disabled = true;
            submitBtn.classList.add('is-loading');
            submitBtn.setAttribute('aria-disabled', 'true');
        });
    });
</script>
@endpush
