<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bill_items', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('bill_id');
            $table->uuid('scheme_id');
            $table->uuid('boq_part_id');
            $table->uuid('boq_item_id');
            $table->timestamps();
            $table->foreign('bill_id')->references('id')->on('bills')->onDelete('cascade');
            $table->foreign('scheme_id')->references('id')->on('schemes')->onDelete('cascade');
            $table->foreign('boq_part_id')->references('id')->on('boq_parts')->onDelete('cascade');
            $table->foreign('boq_item_id')->references('id')->on('boq_items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bill_items');
    }
};
