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
        Schema::create('boq_version_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('project_id')->index();
            $table->uuid('package_id')->index();
            $table->uuid('boq_version_id')->index();
            $table->uuid('boq_part_id')->index();
            $table->uuid('boq_item_id')->index();
            $table->uuid('boq_sub_item_id')->nullable()->index();
            $table->uuid('scheme_option_id')->index();
            $table->uuid('unit_id')->index();
            $table->double('quantity');
            $table->double('rate');
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('restrict');
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('restrict');
            $table->foreign('boq_version_id')->references('id')->on('boq_versions')->onDelete('cascade');
            $table->foreign('boq_part_id')->references('id')->on('boq_parts')->onDelete('restrict');
            $table->foreign('boq_item_id')->references('id')->on('boq_items')->onDelete('restrict');
            $table->foreign('boq_sub_item_id')->references('id')->on('boq_sub_items')->onDelete('restrict');
            $table->foreign('scheme_option_id')->references('id')->on('scheme_options')->onDelete('restrict');
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boq_version_details');
    }
};
