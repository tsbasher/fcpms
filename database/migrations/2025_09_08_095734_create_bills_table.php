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
        Schema::create('bills', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('contractor_id');
            $table->uuid('project_id');
            $table->uuid('package_id');
            $table->uuid('boq_version_id');
            $table->string('bill_no')->nullable();
            $table->date('bill_date')->nullable();
            $table->string('reference_code')->nullable();
            $table->string('name');
            $table->string('status')->default('Pending');
            $table->text('remarks')->nullable();
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->foreign('contractor_id')->references('id')->on('contractors')->onDelete('restrict');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('restrict');
            $table->foreign('boq_version_id')->references('id')->on('boq_versions')->onDelete('restrict');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
