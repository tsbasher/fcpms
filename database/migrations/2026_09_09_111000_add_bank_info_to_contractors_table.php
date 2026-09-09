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
        Schema::table('contractors', function (Blueprint $table) {
            $table->string('bank_account_no')->nullable()->after('contact_person_phone');
            $table->string('bank_name')->nullable()->after('bank_account_no');
            $table->string('branch_name')->nullable()->after('bank_name');
            $table->string('routing_number')->nullable()->after('branch_name');
            $table->text('bank_address')->nullable()->after('routing_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contractors', function (Blueprint $table) {
            $table->dropColumn(['bank_account_no', 'bank_name', 'branch_name', 'routing_number', 'bank_address']);
        });
    }
};