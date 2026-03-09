<div class="page-shell">
    <section class="mx-auto w-full max-w-md">
        <x-ui.surface-card>
            <h1 class="ui-page-title">Forgot Password</h1>
            <p class="ui-small mt-1">Enter your email to receive a password reset link.</p>

            <form id="forgotForm" class="mt-5 space-y-3 [&_.form-group]:mb-0" novalidate>
                <div class="form-group">
                    <label for="forgotEmail">Email</label>
                    <input type="email" id="forgotEmail" class="form-control" placeholder="you@company.com">
                    <span class="error"></span>
                </div>

                <button type="submit" id="forgotSubmitBtn" class="btn btn-primary w-full">Reset Password</button>
                <p id="resetStatus" class="form-status"></p>
            </form>
        </x-ui.surface-card>
    </section>
</div>
