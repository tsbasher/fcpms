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
        Schema::table('boq_parts', function (Blueprint $table) {
            $table->enum('boq_type', ['EGP', 'NONEGP'])->default('NONEGP')->after('code');
            $table->uuid('scheme_option_id')->nullable()->after('boq_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('boq_parts', function (Blueprint $table) {
            $table->dropColumn('boq_type');
            $table->dropColumn('scheme_option_id');
        });
    }
};
