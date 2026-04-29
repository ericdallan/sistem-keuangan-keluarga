<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Public ID for security (Public-facing ID)
            $table->uuid('uuid_users')->unique()->after('id');

            // User Access Control
            $table->enum('role', ['admin', 'user'])->default('user')->after('password');
            $table->enum('position', ['husband', 'wife', 'child'])->after('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Reverse changes
            $table->dropColumn(['uuid_users', 'role', 'position']);
            $table->string('name', 255)->change();
            $table->string('email', 255)->change();
        });
    }
};
