<?php

namespace App\Http\Controllers;

use App\Models\Affiliate;
use App\Models\AffiliateSetting;
use App\Models\Commission;
use App\Models\Payout;
use App\Services\AffiliateService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AffiliateManagementController extends Controller
{
    public function __construct(
        private AffiliateService $affiliateService
    ) {}

    public function index(Request $request): View
    {
        $affiliates = Affiliate::query()
            ->withCount(['referrals', 'commissions'])
            ->withSum(['commissions as pending_commission_sum' => fn ($query) => $query->where('status', 'pending')], 'amount')
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            })
            ->when($request->status, fn ($query, $status) => $query->where('status', $status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $totals = [
            'total' => Affiliate::count(),
            'active' => Affiliate::active()->count(),
            'pending' => Affiliate::pending()->count(),
            'suspended' => Affiliate::suspended()->count(),
            'pending_commissions' => Commission::pending()->count(),
            'pending_payouts' => Payout::where('status', 'pending')->count(),
        ];

        return view('super-admin.affiliates.index', compact('affiliates', 'totals'));
    }

    public function show(Affiliate $affiliate): View
    {
        $summary = $this->affiliateService->summary($affiliate);

        $referrals = $affiliate->referrals()
            ->with('school')
            ->latest()
            ->paginate(10);

        $commissions = $affiliate->commissions()
            ->with(['referral.school', 'plan'])
            ->latest()
            ->paginate(10);

        $payouts = $affiliate->payouts()->latest()->paginate(10);

        return view('super-admin.affiliates.show', compact('affiliate', 'summary', 'referrals', 'commissions', 'payouts'));
    }

    public function activate(Affiliate $affiliate): RedirectResponse
    {
        $this->affiliateService->activate($affiliate);

        return back()->with('success', "Affiliate \"{$affiliate->name}\" has been activated.");
    }

    public function suspend(Affiliate $affiliate): RedirectResponse
    {
        $this->affiliateService->suspend($affiliate);

        return back()->with('success', "Affiliate \"{$affiliate->name}\" has been suspended.");
    }

    public function approveCommission(Affiliate $affiliate, Commission $commission): RedirectResponse
    {
        abort_unless($commission->affiliate_id === $affiliate->id, 403, 'This commission does not belong to the selected affiliate.');

        $this->affiliateService->approveCommission($commission);

        return back()->with('success', 'Commission approved.');
    }

    public function cancelCommission(Affiliate $affiliate, Commission $commission): RedirectResponse
    {
        abort_unless($commission->affiliate_id === $affiliate->id, 403, 'This commission does not belong to the selected affiliate.');

        $this->affiliateService->cancelCommission($commission, 'Cancelled by administrator.');

        return back()->with('success', 'Commission cancelled.');
    }

    public function settings(): View
    {
        $settings = [
            'default_commission_rate' => $this->affiliateService->setting('default_commission_rate', config('affiliate.default_commission_rate')),
            'commission_months' => $this->affiliateService->setting('commission_months', config('affiliate.commission_months')),
            'min_payout_amount' => $this->affiliateService->setting('min_payout_amount', config('affiliate.min_payout_amount')),
        ];

        return view('super-admin.affiliates.settings', compact('settings'));
    }

    public function updateSettings(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'default_commission_rate' => 'required|numeric|min:0|max:100',
            'commission_months' => 'required|integer|min:1|max:60',
            'min_payout_amount' => 'required|numeric|min:0',
        ]);

        foreach ($validated as $key => $value) {
            AffiliateSetting::updateOrCreate(['key' => $key], ['value' => (string) $value]);
        }

        return redirect()
            ->route('affiliates.settings')
            ->with('success', 'Affiliate program settings have been updated.');
    }
}
