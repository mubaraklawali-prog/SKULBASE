<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\ResolvesParentChildren;
use Illuminate\View\View;

class ParentProfileController extends Controller
{
    use ResolvesParentChildren;

    public function __invoke(): View
    {
        $user = auth()->user();
        $parent = $user->parent;

        if (! $parent) {
            abort(404, 'Parent profile not found for this account.');
        }

        $children = $this->resolveParentChildren();

        return view('parent.profile', compact('parent', 'children'));
    }
}
