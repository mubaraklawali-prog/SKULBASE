<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AffiliateService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use InvalidArgumentException;

class AffiliateController extends Controller
{
    public function __construct(
        private AffiliateService $affiliateService
    ) {}

    public function showRegistrationForm(): View
    {
        return view('affiliate.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email|unique:affiliates,email',
            'phone' => 'nullable|string|max:20',
            'password' => ['required', 'string', 'min:8', 'confirmed', Rules\Password::defaults()->mixedCase()->numbers(), 'regex:/^\S+$/'],
            'terms' => 'accepted',
        ]);

        $affiliate = $this->affiliateService->registerWithAccount($validated);

        Auth::login($affiliate->user);

        $request->session()->regenerate();

        return redirect()
            ->route('affiliate.dashboard')
            ->with('success', 'Your affiliate account has been created. It will start tracking referrals once a Skulbase admin approves it.');
    }

    public function showLoginForm(): View
    {
        return view('affiliate.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (! $user || $user->role !== 'affiliate' || ! Hash::check($credentials['password'], $user->password)) {
            return back()
                ->withErrors(['email' => 'Invalid affiliate credentials.'])
                ->withInput();
        }

        Auth::login($user);

        $request->session()->regenerate();

        return redirect()->route('affiliate.dashboard');
    }

    public function dashboard(): View
    {
        $affiliate = auth()->user()->affiliate;

        abort_if(! $affiliate, 404, 'No affiliate profile was found for this account.');

        $summary = $this->affiliateService->summary($affiliate);

        $stats = [
            'total_clicks' => $affiliate->clicks,
            'total_referrals' => $affiliate->referrals()->count(),
            'referred_schools' => $affiliate->referrals()->whereNotNull('school_id')->count(),
            'trial_referrals' => $affiliate->referrals()->whereIn('status', ['registered', 'approved'])->count(),
            'paying_referrals' => $affiliate->referrals()->whereNotNull('first_paid_at')->count(),
            'pending_commission' => $summary['pending'],
            'approved_commission' => $summary['approved'],
            'paid_commission' => $summary['paid'],
            'total_earned' => $summary['approved'] + $summary['paid'],
        ];

        $referrals = $affiliate->referrals()
            ->with('school')
            ->latest()
            ->paginate(10);

        $commissions = $affiliate->commissions()
            ->with(['referral.school', 'plan'])
            ->latest()
            ->paginate(10);

        $payouts = $affiliate->payouts()->latest()->get();

        $minPayout = (float) $this->affiliateService->setting('min_payout_amount', config('affiliate.min_payout_amount'));

        return view('affiliate.dashboard', compact('affiliate', 'stats', 'referrals', 'commissions', 'payouts', 'minPayout'));
    }

    public function requestPayout(Request $request): RedirectResponse
    {
        $affiliate = auth()->user()->affiliate;

        abort_unless($affiliate && $affiliate->isActive(), 403, 'Your affiliate account must be active to request a payout.');

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'method' => 'required|string|max:50',
            'payout_details' => 'nullable|string|max:500',
        ]);

        try {
            $this->affiliateService->requestPayout($affiliate, [
                'amount' => $validated['amount'],
                'method' => $validated['method'],
                'payout_details' => ($validated['payout_details'] ?? null)
                    ? ['details' => $validated['payout_details']]
                    : null,
            ]);
        } catch (InvalidArgumentException $e) {
            return back()
                ->withErrors(['amount' => $e->getMessage()])
                ->withInput();
        }

        return back()->with('success', 'Payout request submitted successfully.');
    }
}
