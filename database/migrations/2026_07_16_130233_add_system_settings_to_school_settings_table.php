<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('school_settings', function (Blueprint $table) {
            $table->string('timezone')->nullable()->after('school_close_time');
            $table->string('date_format')->nullable()->after('timezone');
            $table->string('time_format')->nullable()->after('date_format');
            $table->string('currency')->nullable()->after('time_format');
            $table->string('currency_symbol')->nullable()->after('currency');
            $table->boolean('maintenance_mode')->default(false)->after('currency_symbol');
            $table->text('maintenance_message')->nullable()->after('maintenance_mode');
        });
    }

    public function down(): void
    {
        Schema::table('school_settings', function (Blueprint $table) {
            $table->dropColumn([
                'timezone', 'date_format', 'time_format',
                'currency', 'currency_symbol',
                'maintenance_mode', 'maintenance_message',
            ]);
        });
    }
};
