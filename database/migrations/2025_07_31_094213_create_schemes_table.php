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
        Schema::create('schemes', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->string('name');
            $table->string('code');
            $table->string('alias')->nullable();
            $table->uuid('project_id');
            $table->uuid('package_id');
            $table->text('description')->nullable();
            $table->uuid('division_id')->nullable();
            $table->uuid('district_id')->nullable();
            $table->uuid('upazila_id')->nullable();
            $table->uuid('union_id')->nullable();
            $table->string('village_name')->nullable();
            $table->string('external_code')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->uuid('scheme_option_id')->nullable();
            $table->date('signing_date')->nullable();
            $table->date('planned_start_date')->nullable();
            $table->date('planned_end_date')->nullable();
            $table->date('actual_start_date')->nullable();
            $table->date('actual_end_date')->nullable();
            $table->string('planned_budget')->nullable();
            $table->string('actual_budget')->nullable();
            $table->tinyInteger('is_active')->default(1);
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('restrict');
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('restrict');
            $table->foreign('division_id')->references('id')->on('divisions')->onDelete('restrict');
            $table->foreign('district_id')->references('id')->on('districts')->onDelete('restrict');
            $table->foreign('upazila_id')->references('id')->on('upazilas')->onDelete('restrict');
            $table->foreign('union_id')->references('id')->on('unions')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schemes');
    }
};
