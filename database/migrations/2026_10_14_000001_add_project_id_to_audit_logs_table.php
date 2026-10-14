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
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->uuid('project_id')->nullable()->index();
        });

        // Backfill from the acting admin's / user's current project (approximate
        // for historical rows, but keeps the data consistent going forward).
        DB::statement("
            UPDATE audit_logs al
            SET project_id = a.project_id::uuid
            FROM admins a
            JOIN projects p ON p.id::text = a.project_id
            WHERE al.user_type = 'admin'
              AND al.user_id = a.id
              AND a.project_id IS NOT NULL
              AND a.project_id <> ''
        ");

        DB::statement("
            UPDATE audit_logs al
            SET project_id = u.project_id
            FROM users u
            JOIN projects p ON p.id = u.project_id
            WHERE al.user_type = 'user'
              AND al.user_id = u.id
              AND u.project_id IS NOT NULL
        ");

        Schema::table('audit_logs', function (Blueprint $table) {
            $table->foreign('project_id')->references('id')->on('projects')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->dropForeign(['project_id']);
            $table->dropColumn('project_id');
        });
    }
};
