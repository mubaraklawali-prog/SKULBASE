<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Your School - Skulbase</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            background: linear-gradient(135deg, #0a1628 0%, #1a2d50 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }
        .register-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }
        .register-header {
            background: linear-gradient(135deg, var(--primary, #5B21FF) 0%, #1a1a2e 100%);
            padding: 40px;
            text-align: center;
            color: #fff;
        }
        .register-header h1 {
            font-size: 28px;
            font-weight: 700;
            margin: 0 0 8px 0;
        }
        .register-header p {
            margin: 0;
            opacity: 0.85;
            font-size: 15px;
        }
        .register-body {
            padding: 40px;
        }
        .section-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-heading, #0a1628);
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--border, #e9ecef);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .section-title .badge {
            background: var(--primary, #5B21FF);
            color: #fff;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
        }
        .sb-form-input, .sb-form-select {
            border-radius: 8px;
            border: 1px solid #dee2e6;
            padding: 10px 16px;
            font-size: 14px;
            color: #333;
            background: #fff;
            transition: border-color 0.2s;
            width: 100%;
        }
        .sb-form-input:focus, .sb-form-select:focus {
            border-color: var(--primary, #5B21FF);
            outline: none;
            box-shadow: 0 0 0 3px var(--primary-focus, rgba(124, 58, 237, 0.15));
        }
        .sb-form-label {
            display: block;
            font-weight: 500;
            font-size: 14px;
            color: #333;
            margin-bottom: 6px;
        }
        .sb-form-label .required { color: #dc3545; }
        .sb-form-error { color: #dc3545; font-size: 13px; margin-top: 4px; }
        .sb-btn-primary {
            background: var(--primary, #5B21FF);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 12px 32px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
            width: 100%;
        }
        .sb-btn-primary:hover { background: var(--primary-hover, #6D28D9); }
        .sb-flash-success {
            background: #d1e7dd;
            color: #0f5132;
            padding: 12px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .login-link {
            text-align: center;
            margin-top: 24px;
            font-size: 14px;
            color: #6c757d;
        }
        .login-link a {
            color: var(--primary, #5B21FF);
            text-decoration: none;
            font-weight: 500;
        }
        .login-link a:hover { text-decoration: underline; }
        .plan-cards { display: flex; gap: 16px; flex-wrap: wrap; }
        .plan-card {
            flex: 1;
            min-width: 200px;
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 20px;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
        }
        .plan-card:hover { border-color: var(--primary, #5B21FF); }
        .plan-card.selected { border-color: var(--primary, #5B21FF); background: rgba(91, 33, 255, 0.04); }
        .plan-card input[type="radio"] { display: none; }
        .plan-card-name { font-size: 16px; font-weight: 600; color: #1a1a2e; margin-bottom: 8px; }
        .plan-card-price { font-size: 22px; font-weight: 700; color: var(--primary, #5B21FF); margin-bottom: 4px; }
        .plan-card-price s { font-size: 14px; color: #999; font-weight: 400; margin-right: 4px; }
        .plan-card-detail { font-size: 13px; color: #6c757d; margin-bottom: 2px; }
        .plan-card-trial { font-size: 12px; color: #198754; font-weight: 500; margin-top: 8px; }
        .plan-card-discount { font-size: 12px; color: #dc3545; font-weight: 600; margin-top: 4px; }
        .plan-card-unlimited { font-size: 12px; color: var(--primary, #5B21FF); font-weight: 500; }
        .plan-card-recommended {
            border-color: var(--primary, #5B21FF);
            background: linear-gradient(135deg, rgba(91, 33, 255, 0.04) 0%, rgba(91, 33, 255, 0.02) 100%);
        }
        .plan-card-recommended:hover { background: rgba(91, 33, 255, 0.06); }
        .plan-card-recommended.selected { background: rgba(91, 33, 255, 0.08); }
        .plan-card-badge {
            position: absolute; top: -10px; right: 12px;
            background: var(--primary, #5B21FF); color: #fff;
            font-size: 11px; font-weight: 700; padding: 3px 10px;
            border-radius: 12px; text-transform: uppercase; letter-spacing: 0.5px;
        }
        .plan-card-free-price { font-size: 28px; font-weight: 700; color: var(--primary, #5B21FF); margin-bottom: 4px; }
        .plan-card-message { font-size: 13px; color: #555; margin-top: 8px; line-height: 1.4; }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="register-card">
                    <div class="register-header">
                        <h1>Register Your School</h1>
                        <p>Join Skulbase and transform your school management experience</p>
                    </div>
                    <div class="register-body">
                        @if (session('registration_success'))
                            <div class="sb-flash-success">
                                {{ session('registration_success') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger" style="border-radius: 8px;">
                                <ul style="margin: 0; padding-left: 16px;">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('school.register.submit') }}">
                            @csrf

                            {{-- School Information --}}
                            <div class="section-title">
                                <span class="badge">1</span> School Information
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="school-name" class="sb-form-label">School Name <span class="required">*</span></label>
                                    <input type="text" name="school_name" id="school-name" class="sb-form-input @error('school_name') is-invalid @enderror"
                                           value="{{ old('school_name') }}" placeholder="e.g. Greenfield Academy" required>
                                    @error('school_name')
                                        <div class="sb-form-error">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="school-type" class="sb-form-label">School Type</label>
                                    <select name="school_type" id="school-type" class="sb-form-select @error('school_type') is-invalid @enderror">
                                        <option value="">Select type...</option>
                                        <option value="Primary" {{ old('school_type') === 'Primary' ? 'selected' : '' }}>Primary</option>
                                        <option value="Secondary" {{ old('school_type') === 'Secondary' ? 'selected' : '' }}>Secondary</option>
                                        <option value="Primary & Secondary" {{ old('school_type') === 'Primary & Secondary' ? 'selected' : '' }}>Primary & Secondary</option>
                                        <option value="Nursery" {{ old('school_type') === 'Nursery' ? 'selected' : '' }}>Nursery</option>
                                        <option value="University" {{ old('school_type') === 'University' ? 'selected' : '' }}>University</option>
                                    </select>
                                    @error('school_type')
                                        <div class="sb-form-error">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="school-email" class="sb-form-label">School Email <span class="required">*</span></label>
                                    <input type="email" name="school_email" id="school-email" class="sb-form-input @error('school_email') is-invalid @enderror"
                                           value="{{ old('school_email') }}" placeholder="school@example.com" required>
                                    @error('school_email')
                                        <div class="sb-form-error">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="school-phone" class="sb-form-label">Phone</label>
                                    <input type="text" name="school_phone" id="school-phone" class="sb-form-input @error('school_phone') is-invalid @enderror"
                                           value="{{ old('school_phone') }}" placeholder="Phone number">
                                    @error('school_phone')
                                        <div class="sb-form-error">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="school-address" class="sb-form-label">Address</label>
                                    <input type="text" name="school_address" id="school-address" class="sb-form-input @error('school_address') is-invalid @enderror"
                                           value="{{ old('school_address') }}" placeholder="Street address">
                                    @error('school_address')
                                        <div class="sb-form-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- School Administrator --}}
                            <div class="section-title mt-4">
                                <span class="badge">2</span> School Administrator
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="admin-name" class="sb-form-label">Full Name <span class="required">*</span></label>
                                    <input type="text" name="admin_name" id="admin-name" class="sb-form-input @error('admin_name') is-invalid @enderror"
                                           value="{{ old('admin_name') }}" placeholder="Administrator's full name" required>
                                    @error('admin_name')
                                        <div class="sb-form-error">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="admin-email" class="sb-form-label">Email <span class="required">*</span></label>
                                    <input type="email" name="admin_email" id="admin-email" class="sb-form-input @error('admin_email') is-invalid @enderror"
                                           value="{{ old('admin_email') }}" placeholder="admin@example.com" required>
                                    @error('admin_email')
                                        <div class="sb-form-error">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="admin-phone" class="sb-form-label">Phone</label>
                                    <input type="text" name="admin_phone" id="admin-phone" class="sb-form-input @error('admin_phone') is-invalid @enderror"
                                           value="{{ old('admin_phone') }}" placeholder="Phone number">
                                    @error('admin_phone')
                                        <div class="sb-form-error">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="admin-password" class="sb-form-label">Password <span class="required">*</span></label>
                                    <input type="password" name="password" id="admin-password" class="sb-form-input @error('password') is-invalid @enderror"
                                           placeholder="Min. 8 characters" required>
                                    @error('password')
                                        <div class="sb-form-error">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="admin-password-confirm" class="sb-form-label">Confirm Password <span class="required">*</span></label>
                                    <input type="password" name="password_confirmation" id="admin-password-confirm" class="sb-form-input"
                                           placeholder="Re-enter password" required>
                                </div>
                            </div>

                            {{-- Subscription --}}
                            <div class="section-title mt-4">
                                <span class="badge">3</span> Subscription
                            </div>
                            <div class="mb-3">
                                <label class="sb-form-label">Choose a Plan <span class="required">*</span></label>
                                <div class="plan-cards">
                                    @foreach ($plans as $plan)
                                        @if($plan->slug === 'free-trial')
                                            <label class="plan-card plan-card-recommended {{ old('plan_id') == $plan->id ? 'selected' : '' }}" onclick="selectPlan(this)">
                                                <span class="plan-card-badge">Recommended</span>
                                                <input type="radio" name="plan_id" value="{{ $plan->id }}" {{ old('plan_id') == $plan->id ? 'checked' : '' }}>
                                                <div class="plan-card-name">{{ $plan->name }}</div>
                                                <div class="plan-card-free-price">FREE</div>
                                                <div class="plan-card-detail">30 days, no payment required</div>
                                                <div class="plan-card-message">Try Skulbase FREE for 30 days. No payment required. Experience the system with your school, then choose the plan that fits you.</div>
                                            </label>
                                        @else
                                            <label class="plan-card {{ old('plan_id') == $plan->id ? 'selected' : '' }}" onclick="selectPlan(this)">
                                                <input type="radio" name="plan_id" value="{{ $plan->id }}" {{ old('plan_id') == $plan->id ? 'checked' : '' }}>
                                                <div class="plan-card-name">{{ $plan->name }}</div>
                                                @if($plan->monthly_price == 0 && $plan->yearly_price == 0)
                                                    <div class="plan-card-price">₦0</div>
                                                @elseif($plan->isDiscountActive() && in_array($plan->discount_scope, ['monthly', 'both']))
                                                    <div class="plan-card-price">
                                                        <s>{{ $plan->formattedMonthlyPrice() }}</s>
                                                        {{ $plan->formattedDiscountedMonthlyPrice() }}
                                                    </div>
                                                @else
                                                    <div class="plan-card-price">{{ $plan->formattedMonthlyPrice() }}</div>
                                                @endif
                                                <div class="plan-card-detail">per month</div>
                                                @if($plan->monthly_price > 0 || $plan->yearly_price > 0)
                                                    @if($plan->isDiscountActive() && in_array($plan->discount_scope, ['annual', 'both']))
                                                        <div class="plan-card-detail">
                                                            Yearly: <s>{{ $plan->formattedYearlyPrice() }}</s>
                                                            <strong style="color: #dc3545;">{{ $plan->formattedDiscountedYearlyPrice() }}</strong>
                                                        </div>
                                                    @else
                                                        <div class="plan-card-detail">Yearly: {{ $plan->formattedYearlyPrice() }}</div>
                                                    @endif
                                                @endif
                                                @if($plan->is_unlimited)
                                                    <div class="plan-card-unlimited">Unlimited students</div>
                                                @else
                                                    <div class="plan-card-detail">{{ number_format($plan->student_limit ?? 0) }} students</div>
                                                @endif
                                                <div class="plan-card-trial">{{ $plan->trial_days }}-day free trial</div>
                                                @if($plan->isDiscountActive())
                                                    <div class="plan-card-discount">{{ rtrim(rtrim(number_format($plan->discount_percentage, 2), '0'), '.') }}% OFF — {{ $plan->discount_scope_label }}</div>
                                                @endif
                                            </label>
                                        @endif
                                    @endforeach
                                </div>
                                @error('plan_id')
                                    <div class="sb-form-error">{{ $message }}</div>
                                @enderror
                            </div>
                                <div class="col-md-12 mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" name="terms" id="terms" class="form-check-input @error('terms') is-invalid @enderror"
                                               value="1" {{ old('terms') ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="terms" style="font-size: 14px;">
                                            I agree to the <a href="#" style="color: var(--primary, #5B21FF);">Terms of Service</a> and <a href="#" style="color: var(--primary, #5B21FF);">Privacy Policy</a>
                                        </label>
                                    </div>
                                    @error('terms')
                                        <div class="sb-form-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="sb-btn-primary">
                                    Submit Registration
                                </button>
                            </div>
                        </form>

                        <div class="login-link">
                            Already have an account? <a href="{{ route('login') }}">Login here</a>
                        </div>
                    </div>
                </div>
                <div style="text-align: center; margin-top: 24px; color: rgba(255,255,255,0.6); font-size: 12px;">
                    &copy; {{ date('Y') }} Skulbase. All Rights Reserved. | Designed &amp; Developed by Mubarak Lawal
                </div>
            </div>
        </div>
    </div>
    <script>
        function selectPlan(el) {
            document.querySelectorAll('.plan-card').forEach(function(card) {
                card.classList.remove('selected');
            });
            el.classList.add('selected');
            el.querySelector('input[type="radio"]').checked = true;
        }
    </script>
</body>
</html>
