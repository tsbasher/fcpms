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
        Schema::create('measurements', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('project_id');
             $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->uuid('bill_id');
            $table->uuid('bill_detail_id');
            $table->uuid('scheme_id');
            $table->uuid('boq_part_id');
            $table->uuid('boq_item_id');
            $table->uuid('boq_subitem_id')->nullable();
            $table->text('description');
            $table->uuid('unit_id');
            $table->integer('nos')->default(0);
            $table->decimal('length', 15, 4)->nullable();
            $table->decimal('width', 15, 4)->nullable();
            $table->decimal('height', 15, 4)->nullable();
            $table->decimal('weight', 15, 4)->nullable();
            $table->decimal('quantity', 15, 4)->default(0);

             $table->foreign('bill_id')->references('id')->on('bills')->onDelete('cascade');
             $table->foreign('bill_detail_id')->references('id')->on('bill_details')->onDelete('cascade');
            $table->foreign('scheme_id')->references('id')->on('schemes')->onDelete('cascade');
            $table->foreign('boq_part_id')->references('id')->on('boq_parts')->onDelete('cascade');
            $table->foreign('boq_item_id')->references('id')->on('boq_items')->onDelete('cascade');
            $table->foreign('boq_subitem_id')->references('id')->on('boq_sub_items')->onDelete('cascade');
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('measurements');
    }
};
