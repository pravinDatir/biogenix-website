@php
    $requestedType = request('user_type', request('portal'));
    if (! in_array($requestedType, ['b2b', 'b2c'], true)) {
        $requestedType = old('user_type') === 'b2b' ? 'b2b' : 'b2c';
    }

    $portal = $requestedType === 'b2b' ? 'b2b' : 'b2c';
    $selectedCustomerType = old('b2b_type', 'distributor');

    $views = [
        'b2c' => [
            'leftBadge' => 'JOIN BIOGENIX RETAIL',
            'leftTitle' => 'Create your personal buying account.',
            'leftCopy' => 'Register as a retail customer to access MRP-oriented catalog visibility, self quotations, and personal order workflows.',
            'infoBadge' => 'B2C Signup',
            'infoTitle' => 'Retail Customer Onboarding',
            'infoCopy' => 'Create your personal healthcare buying account with MRP-oriented catalog and self-service order workflows.',
            'infoCard' => 'border border-emerald-400/30 bg-emerald-900/40 backdrop-blur-md',
            'infoBadgeClass' => 'bg-emerald-500/20 text-emerald-100 border border-emerald-400/30',
            'infoDotClass' => 'bg-emerald-400',
            'infoItems' => [
                'Retail access and self profile scope',
                'Own quotations, own orders, own support',
            ],
            'notice' => null,
            'bgGradient' => 'from-emerald-900 via-slate-900 to-slate-950',
            'activeButtonClass' => 'bg-emerald-600 text-white shadow-lg shadow-emerald-500/30 border-emerald-500',
        ],
        'b2b' => [
            'leftBadge' => 'JOIN BIOGENIX BUSINESS',
            'leftTitle' => 'Create your business access account.',
            'leftCopy' => 'Register as a distributor, dealer, lab, or hospital for account-specific pricing and approval-aware workflows.',
            'infoBadge' => 'B2B Signup',
            'infoTitle' => 'Business Account Onboarding',
            'infoCopy' => 'Register your distributor, dealer, lab, or hospital profile for business pricing and approval-aware workflows.',
            'infoCard' => 'border border-blue-400/30 bg-blue-900/40 backdrop-blur-md',
            'infoBadgeClass' => 'bg-blue-500/20 text-blue-100 border border-blue-400/30',
            'infoDotClass' => 'bg-blue-400',
            'infoItems' => [
                'Business pricing visibility based on permissions',
                'PI generation and company context controls',
            ],
            'notice' => 'B2B accounts are activated only after admin approval.',
            'bgGradient' => 'from-blue-900 via-slate-900 to-slate-950',
            'activeButtonClass' => 'bg-blue-600 text-white shadow-lg shadow-blue-500/30 border-blue-500',
        ],
    ];

    $view = $views[$portal];

    $customerTypeOptions = $portal === 'b2b'
        ? [
            'dealer' => 'Dealer',
            'distributor' => 'Distributor',
            'lab' => 'Lab',
            'hospital' => 'Hospital',
        ]
        : ['retail' => 'Retail'];
@endphp


@if ($portal === 'b2c')
    @include('pages.auth.partials.signup-b2c', get_defined_vars())
@else
    @include('pages.auth.partials.signup-b2b', get_defined_vars())
@endif

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const customerType = document.getElementById('customerType');
        const userType = document.getElementById('userType');
        const b2bType = document.getElementById('b2bType');
        const organizationField = document.getElementById('company_name');

        function syncType() {
            const portal = '{{ $portal }}';
            const selected = customerType ? customerType.value : 'retail';

            if (userType) userType.value = portal;

            if (portal === 'b2b') {
                if (b2bType) b2bType.value = selected || 'distributor';
                if (organizationField) organizationField.required = true;
            } else {
                if (b2bType) b2bType.value = '';
                if (organizationField) organizationField.required = false;
            }
        }

        if (customerType) {
            customerType.addEventListener('change', syncType);
            syncType();
        }

        const signupForm = document.getElementById('signupForm');
        if (signupForm) {
            signupForm.addEventListener('submit', function () {
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.classList.add('is-loading');
                }
            });
        }
    });
</script>
@endpush
