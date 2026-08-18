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
        Schema::create('measurement_details', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('measurement_id');
            $table->uuid('project_id');
            $table->uuid('bill_id');
            $table->uuid('bill_detail_id');
            $table->uuid('scheme_id');
            $table->uuid('boq_part_id');
            $table->uuid('boq_item_id');
            $table->uuid('boq_subitem_id')->nullable();
            $table->text('description');
            $table->uuid('unit_id');
            $table->string('dia')->nullable();
            $table->string('spacing')->nullable();
            $table->integer('rebar_nos')->default(0);
            $table->decimal('rebar_length', 15, 4)->nullable();
            $table->decimal('unit_weight', 15, 4)->nullable();
            $table->decimal('quantity_per_pile', 15, 4)->default(0);
             $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('bill_id')->references('id')->on('bills')->onDelete('cascade');
            $table->foreign('bill_detail_id')->references('id')->on('bill_details')->onDelete('cascade');
            $table->foreign('measurement_id')->references('id')->on('measurements')->onDelete('cascade');
            $table->foreign('scheme_id')->references('id')->on('schemes')->onDelete('restrict');
            $table->foreign('boq_part_id')->references('id')->on('boq_parts')->onDelete('restrict');
            $table->foreign('boq_item_id')->references('id')->on('boq_items')->onDelete('restrict');
            $table->foreign('boq_subitem_id')->references('id')->on('boq_sub_items')->onDelete('restrict');
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('measurement_details');
    }
};
