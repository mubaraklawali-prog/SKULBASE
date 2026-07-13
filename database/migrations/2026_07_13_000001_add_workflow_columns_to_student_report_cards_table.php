<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_report_cards', function (Blueprint $table) {
            $table->foreignId('submitted_by')->nullable()->constrained('users')->nullOnDelete()->after('status');
            $table->timestamp('submitted_at')->nullable()->after('submitted_by');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete()->after('submitted_at');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
            $table->foreignId('published_by')->nullable()->constrained('users')->nullOnDelete()->after('approved_at');
            $table->text('rejection_reason')->nullable()->after('published_by');

            $table->index('status', 'src_status_idx');
        });
    }

    public function down(): void
    {
        Schema::table('student_report_cards', function (Blueprint $table) {
            $table->dropForeign(['submitted_by']);
            $table->dropForeign(['approved_by']);
            $table->dropForeign(['published_by']);
            $table->dropColumn([
                'submitted_by', 'submitted_at',
                'approved_by', 'approved_at',
                'published_by', 'rejection_reason',
            ]);
            $table->dropIndex('src_status_idx');
        });
    }
};
