<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckSchoolApproval
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (! $user || $user->role !== 'school_admin') {
            return $next($request);
        }

        $school = $user->school;

        if (! $school) {
            return $next($request);
        }

        if ($school->registration_status === 'pending') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->with('pending_approval', 'Your school registration is currently awaiting approval from Skulbase. You will receive access once your application has been reviewed.');
        }

        if ($school->registration_status === 'rejected') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->with('rejected', 'Your school registration has been rejected. Please contact support for more information.');
        }

        return $next($request);
    }
}
