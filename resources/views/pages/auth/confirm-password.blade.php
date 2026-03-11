<div class="min-h-screen bg-slate-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8 bg-gradient-to-br from-slate-50 to-blue-50/50">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="text-center mb-8">
            <a href="/" class="inline-flex items-center justify-center h-12 w-12 rounded-xl bg-blue-600 shadow-lg text-white mb-6">
                <!-- Shield Check Icon -->
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.956 11.956 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
            </a>
            <h1 class="text-3xl font-bold tracking-tight text-slate-900">Confirm Password</h1>
            <p class="mt-2 text-sm text-slate-600">Please confirm your password to continue.</p>
        </div>

        <div class="bg-white py-8 px-4 shadow-xl sm:rounded-2xl sm:px-10 border border-slate-100">
            <form id="confirmPasswordForm" method="POST" action="{{ route('password.confirm.store') }}" class="space-y-6" novalidate>
                @csrf

                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-700 mb-2">Password</label>
                    <input 
                        id="password" 
                        name="password" 
                        type="password" 
                        class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-slate-900 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 hover:border-blue-400 @error('password') border-red-500 ring-2 ring-red-200 @enderror" 
                        placeholder="Enter your password"
                        required
                    >
                    @error('password')
                        <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                    <span class="error"></span>
                </div>

                <div class="pt-2">
                    <button id="confirmPasswordSubmitBtn" class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-blue-500/30 hover:shadow-blue-500/40 transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" type="submit">
                        Confirm Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('confirmPasswordForm');
        const submitBtn = document.getElementById('confirmPasswordSubmitBtn');
        if (!form || !submitBtn) return;

        form.addEventListener('submit', function () {
            submitBtn.disabled = true;
            submitBtn.classList.add('is-loading');
            submitBtn.setAttribute('aria-disabled', 'true');
        });
    });
</script>
@endpush
