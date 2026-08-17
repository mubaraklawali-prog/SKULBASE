<?php

namespace App\Http\Controllers;

use App\Models\Payout;
use App\Services\AffiliateService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AffiliatePayoutController extends Controller
{
    public function __construct(
        private AffiliateService $affiliateService
    ) {}

    public function index(Request $request): View
    {
        $payouts = Payout::query()
            ->with('affiliate')
            ->when($request->status, fn ($query, $status) => $query->where('status', $status))
            ->latest('requested_at')
            ->paginate(15)
            ->withQueryString();

        $totals = [
            'total' => Payout::count(),
            'pending' => Payout::where('status', 'pending')->count(),
            'processing' => Payout::where('status', 'processing')->count(),
            'paid' => Payout::where('status', 'paid')->count(),
            'cancelled' => Payout::where('status', 'cancelled')->count(),
            'pending_amount' => Payout::where('status', 'pending')->sum('amount'),
        ];

        return view('super-admin.payouts.index', compact('payouts', 'totals'));
    }

    public function approve(Request $request, Payout $payout): RedirectResponse
    {
        $validated = $request->validate([
            'reference' => 'nullable|string|max:255',
        ]);

        $this->affiliateService->approvePayout($payout, $validated['reference'] ?? null);

        return back()->with('success', 'Payout marked as paid.');
    }

    public function reject(Request $request, Payout $payout): RedirectResponse
    {
        $validated = $request->validate([
            'note' => 'nullable|string|max:500',
        ]);

        $this->affiliateService->rejectPayout($payout, $validated['note'] ?? null);

        return back()->with('success', 'Payout request rejected.');
    }
}
