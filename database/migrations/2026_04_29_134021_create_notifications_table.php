<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            // Receiver of the notification
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Polymorphic Relationship (Can link to Expense OR FundRequest)
            $table->unsignedBigInteger('notifiable_id');
            $table->string('notifiable_type', 100);

            $table->json('data'); // Store custom messages/labels
            $table->timestamp('read_at')->nullable(); // For "Mark as Read" feature
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
