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
        Schema::create('packages', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('project_id');
            $table->string('name');
            $table->string('code');
            $table->string('alias')->nullable();
            $table->uuid('division_id')->nullable();
            
            $table->uuid('region_id')->nullable();
            $table->uuid('district_id')->nullable();
            $table->text('description')->nullable();
            $table->date('bid_invitation_date')->nullable();
            $table->date('bid_submission_date')->nullable();
            $table->date('planned_start_date')->nullable();
            $table->date('planned_end_date')->nullable();
            $table->date('actual_start_date')->nullable();
            $table->date('actual_end_date')->nullable();
            $table->string('planned_budget')->nullable();
            $table->string('actual_budget')->nullable();
            $table->tinyInteger('is_active')->default(1);
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
