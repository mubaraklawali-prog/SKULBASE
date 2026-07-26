<?php

namespace App\Http\Controllers\Traits;

use App\Models\Student;
use Illuminate\Database\Eloquent\Collection;

trait ResolvesParentChildren
{
    protected function resolveParentChildren(): Collection
    {
        $user = auth()->user();
        $parent = $user->parent;

        if (! $parent) {
            abort(404, 'Parent profile not found for this account.');
        }

        return $parent->children()
            ->where('students.status', 'active')
            ->with(['schoolClass', 'section'])
            ->get();
    }

    protected function resolveSelectedChild(Collection $children, ?int $studentId = null): ?Student
    {
        if ($children->count() === 1 && ! $studentId) {
            return $children->first();
        }

        if ($studentId) {
            return $children->firstWhere('id', $studentId);
        }

        return null;
    }
}
