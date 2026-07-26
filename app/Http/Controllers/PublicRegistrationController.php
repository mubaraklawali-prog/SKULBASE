<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PublicRegistrationController extends Controller
{
    public function showForm(): View
    {
        $plans = Plan::active()->ordered()->get();

        return view('auth.register-school', compact('plans'));
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'school_name' => 'required|string|max:255',
            'school_type' => 'nullable|string|max:100',
            'school_email' => 'required|email|max:255|unique:schools,email',
            'school_phone' => 'nullable|string|max:20',
            'school_address' => 'nullable|string|max:255',
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|max:255|unique:users,email',
            'admin_phone' => 'nullable|string|max:20',
            'plan_id' => 'required|exists:plans,id',
            'terms' => 'accepted',
        ]);

        $temporaryPassword = Str::random(12);
        $slug = Str::slug($validated['school_name']);

        $existingSlugCount = School::where('slug', $slug)->count();
        if ($existingSlugCount > 0) {
            $slug = $slug.'-'.$existingSlugCount;
        }

        DB::transaction(function () use ($validated, $temporaryPassword, $slug) {
            $school = School::create([
                'name' => $validated['school_name'],
                'slug' => $slug,
                'school_type' => $validated['school_type'] ?? null,
                'email' => $validated['school_email'],
                'phone' => $validated['school_phone'] ?? null,
                'address' => $validated['school_address'] ?? null,
                'country' => 'Nigeria',
                'is_active' => false,
                'registration_status' => 'pending',
                'registered_at' => now(),
            ]);

            $user = User::create([
                'name' => $validated['admin_name'],
                'email' => $validated['admin_email'],
                'password' => Hash::make($temporaryPassword),
            ]);

            $user->forceFill([
                'role' => 'school_admin',
                'school_id' => $school->id,
            ])->save();

            $school->update([
                'registered_at' => now(),
            ]);
        });

        return redirect()
            ->route('login')
            ->with('registration_success', 'Your school registration has been submitted successfully! Your application is now pending approval. You will receive access once your application has been reviewed.');
    }
}
