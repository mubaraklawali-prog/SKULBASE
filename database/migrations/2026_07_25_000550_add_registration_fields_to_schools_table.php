<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->string('school_type')->nullable()->after('name');
            $table->string('registration_status')->nullable()->after('is_active');
            $table->timestamp('registered_at')->nullable()->after('registration_status');
            $table->timestamp('approved_at')->nullable()->after('registered_at');
            $table->timestamp('rejected_at')->nullable()->after('approved_at');
            $table->text('rejection_reason')->nullable()->after('rejected_at');
        });
    }

    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->dropColumn([
                'school_type',
                'registration_status',
                'registered_at',
                'approved_at',
                'rejected_at',
                'rejection_reason',
            ]);
        });
    }
};
