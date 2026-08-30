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
        Schema::table('users', function (Blueprint $table) {
            $table->tinyInteger('login_attempts')->default(0)->after('is_locked');
            $table->timestamp('locked_until')->nullable()->after('login_attempts');
        });

        Schema::table('admins', function (Blueprint $table) {
            $table->tinyInteger('is_active')->default(1)->after('project_code');
            $table->tinyInteger('login_attempts')->default(0)->after('is_active');
            $table->timestamp('locked_until')->nullable()->after('login_attempts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['login_attempts', 'locked_until']);
        });

        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn(['is_active', 'login_attempts', 'locked_until']);
        });
    }
};
