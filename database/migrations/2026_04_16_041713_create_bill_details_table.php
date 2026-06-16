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
        Schema::create('bill_details', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('project_id');
             $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->uuid('bill_id');
            $table->uuid('scheme_id');
            $table->uuid('scheme_option_id');
            $table->uuid('boq_part_id');
            $table->uuid('boq_item_id');
            $table->uuid('boq_subitem_id')->nullable();
            $table->decimal('quantity', 15, 4)->default(0);
            $table->decimal('previous_quantity', 15, 4)->default(0);
            $table->decimal('boq_quantity', 15, 4)->default(0);
            $table->decimal('held_up_quantity', 15, 4)->default(0);
            $table->decimal('this_bill_quantity', 15, 4)->default(0);
            $table->decimal('rate', 15, 2)->default(0);
            $table->decimal('amount', 15, 2)->default(0);
            $table->decimal('this_bill_amount', 15, 2)->default(0);
             $table->foreign('bill_id')->references('id')->on('bills')->onDelete('cascade');
            $table->foreign('scheme_id')->references('id')->on('schemes')->onDelete('cascade');
            $table->foreign('boq_part_id')->references('id')->on('boq_parts')->onDelete('cascade');
            $table->foreign('boq_item_id')->references('id')->on('boq_items')->onDelete('cascade');
            $table->foreign('boq_subitem_id')->references('id')->on('boq_sub_items')->onDelete('cascade');
            $table->foreign('scheme_option_id')->references('id')->on('scheme_options')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bill_details');
    }
};
