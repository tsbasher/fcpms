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
        Schema::table('bills', function (Blueprint $table) {
            $table->decimal('physical_target_progress', 5, 2)->nullable()->after('measurement_to_date');
            $table->decimal('physical_actual_progress', 5, 2)->nullable()->after('physical_target_progress');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bills', function (Blueprint $table) {
            $table->dropColumn(['physical_target_progress', 'physical_actual_progress']);
        });
    }
};