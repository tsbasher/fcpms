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
            //
            $table->date('measurement_from_date')->nullable()->after('reference_code');
            $table->date('measurement_to_date')->nullable()->after('measurement_from_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bills', function (Blueprint $table) {
            //
            $table->dropColumn(['measurement_from_date', 'measurement_to_date']);
        });
    }
};
