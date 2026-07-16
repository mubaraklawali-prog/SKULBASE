<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(): View
    {
        $school = $this->resolveSchool();

        return view('settings.index', compact('school'));
    }

    public function update(Request $request): RedirectResponse
    {
        $school = $this->resolveSchool();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'motto' => 'nullable|string|max:500',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'website' => 'nullable|url|max:255',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            if ($school->logo && Storage::disk('public')->exists($school->logo)) {
                Storage::disk('public')->delete($school->logo);
            }

            $validated['logo'] = $request->file('logo')->store('schools', 'public');
        }

        if ($request->boolean('remove_logo') && $school->logo) {
            if (Storage::disk('public')->exists($school->logo)) {
                Storage::disk('public')->delete($school->logo);
            }
            $validated['logo'] = null;
        }

        unset($validated['remove_logo']);

        $school->update($validated);

        return redirect()
            ->route('settings.index')
            ->with('success', 'School profile updated successfully.');
    }

    private function resolveSchool()
    {
        $user = auth()->user();

        abort_unless($user->school_id, 403, 'No school assigned to your account.');

        return $user->school;
    }
}
