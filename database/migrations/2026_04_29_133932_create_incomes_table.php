<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incomes', function (Blueprint $table) {
            $table->id(); // Internal Primary Key
            $table->uuid('uuid_incomes')->unique(); // Public Unique ID

            // Relation to Users table (Cascade delete if user is removed)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            $table->decimal('amount', 15, 2); // Max 999 Trillion with 2 decimal places
            $table->text('description');
            $table->date('date');

            // Source of Income
            $table->enum('category', ['salary', 'bonus', 'fund_request', 'other']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incomes');
    }
};
