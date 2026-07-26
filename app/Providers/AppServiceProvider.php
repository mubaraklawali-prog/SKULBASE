<?php

namespace App\Providers;

use App\Models\Teacher;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('layouts.app', function ($view) {
            $user = auth()->user();

            if ($user && $user->role === 'teacher') {
                $teacher = request()->attributes->get('teacher');

                if (! $teacher) {
                    $teacher = Teacher::where('school_id', $user->school_id)
                        ->where('email', $user->email)
                        ->first();
                }

                $view->with('currentTeacher', $teacher);
            }
        });
    }
}
