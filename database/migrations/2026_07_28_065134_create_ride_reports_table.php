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
        Schema::create('ride_reports', function (Blueprint $table) {
            $table->id();
            $table->string('ride_id')->unique();
            $table->foreignId('student_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('driver_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('pickup_label')->nullable();
            $table->string('destination_label')->nullable();
            $table->decimal('fare', 10, 2)->nullable();
            $table->string('status')->default('pending');
            $table->text('notes')->nullable();
            $table->text('cancel_reason')->nullable();
            $table->timestamp('ride_created_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ride_reports');
    }
};
