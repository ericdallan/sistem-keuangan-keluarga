<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid_expenses')->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            $table->decimal('amount', 15, 2);
            $table->text('description');
            $table->date('date');

            // File Upload Path (Receipt/Evidence)
            $table->string('evidence_path', 255)->nullable();

            // Approval Status
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
