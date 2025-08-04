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
        Schema::create('boq_sub_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('boq_part_id')->index();
            $table->uuid('boq_item_id')->index();
            $table->uuid('project_id')->index();
            $table->uuid('unit_id');
            $table->string('name');
            $table->string('code');
            $table->string('specification_no')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boq_sub_items');
    }
};
