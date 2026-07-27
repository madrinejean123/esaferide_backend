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
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->string('ride_id')->nullable();
            $table->foreignId('driver_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('student_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('student_firebase_uid')->nullable();
            $table->integer('duration_seconds')->default(0);
            $table->decimal('fare', 10, 2)->default(0);
            $table->json('pickup')->nullable();
            $table->json('destination')->nullable();
            $table->string('pickup_label')->nullable();
            $table->string('destination_label')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
