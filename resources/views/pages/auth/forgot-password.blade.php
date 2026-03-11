<div class="min-h-screen bg-slate-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8 bg-gradient-to-br from-slate-50 to-blue-50/50">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="text-center mb-8">
            <a href="/" class="inline-flex items-center justify-center h-12 w-12 rounded-xl bg-blue-600 shadow-lg text-white mb-6">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
            </a>
            <h1 class="text-3xl font-bold tracking-tight text-slate-900">Reset Password</h1>
            <p class="mt-2 text-sm text-slate-600">Enter your email and we'll send a recovery link.</p>
        </div>

        <div class="bg-white py-8 px-4 shadow-xl sm:rounded-2xl sm:px-10 border border-slate-100">
            <form id="forgotForm" class="space-y-6" novalidate>
                <div>
                    <label for="forgotEmail" class="block text-sm font-semibold text-slate-700 mb-2">Email Address</label>
                    <input 
                        type="email" 
                        id="forgotEmail" 
                        class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-slate-900 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 hover:border-blue-400" 
                        placeholder="you@company.com"
                        required
                    >
                    <span class="error"></span>
                </div>

                <div class="pt-2">
                    <button type="submit" id="forgotSubmitBtn" class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-blue-500/30 hover:shadow-blue-500/40 transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Send Recovery Link
                    </button>
                </div>
                
                <p id="resetStatus" class="form-status mt-2 text-center text-sm"></p>
                
                <div class="text-center pt-2">
                    <a href="{{ route('login') }}" class="font-semibold text-sm text-slate-500 hover:text-slate-800 transition-colors flex items-center justify-center">
                        <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                        Back to Login
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
