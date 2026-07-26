<?php

namespace App\Traits;

trait HasPermissions
{
    /**
     * Check if the teacher has a specific permission.
     *
     * The permission name maps directly to a boolean column on the teachers table.
     * Future permissions can be added by creating a new boolean column and calling
     * hasPermission('column_name') or defining a dedicated method.
     */
    public function hasPermission(string $permission): bool
    {
        return (bool) ($this->attributes[$permission] ?? false);
    }

    public function canMarkAttendance(): bool
    {
        return $this->hasPermission('can_mark_attendance');
    }
}
