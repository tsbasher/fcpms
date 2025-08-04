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
        Schema::create('projects', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->string('name');
            $table->string('code');
            $table->string('short_name');
            $table->text('description')->nullable();
            $table->date('approval_date')->nullable();
            $table->date('planned_start_date')->nullable();
            $table->date('planned_end_date')->nullable();
            $table->date('actual_start_date')->nullable();
            $table->date('actual_end_date')->nullable();
            $table->string('budget')->nullable();
            $table->string('funded_by')->nullable();
            $table->string('pd_name')->nullable();
            $table->string('pd_contact_no')->nullable();
            $table->string('pd_email')->nullable();
            $table->string('ministry')->nullable();
            $table->string('executing_agency')->nullable();
            $table->string('consulting_agency')->nullable();
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
        Schema::dropIfExists('projects');
    }
};
