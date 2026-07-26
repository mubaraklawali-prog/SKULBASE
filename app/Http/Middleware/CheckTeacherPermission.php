<?php

namespace App\Http\Middleware;

use App\Models\Teacher;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTeacherPermission
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = $request->user();

        if (! $user || $user->role !== 'teacher') {
            abort(403, 'Unauthorized action.');
        }

        $teacher = Teacher::where('school_id', $user->school_id)
            ->where('email', $user->email)
            ->first();

        if (! $teacher || ! $teacher->hasPermission($permission)) {
            abort(403, 'You do not have permission to access this resource.');
        }

        $request->attributes->set('teacher', $teacher);

        return $next($request);
    }
}
