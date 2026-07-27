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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('full_name');
            $table->string('reg_number')->nullable();
            $table->string('course')->nullable();
            $table->string('year')->nullable();
            $table->string('phone')->nullable();
            $table->string('photo_url')->nullable();
            $table->boolean('accessibility_wheelchair')->default(false);
            $table->boolean('accessibility_visual')->default(false);
            $table->boolean('accessibility_hearing')->default(false);
            $table->boolean('accessibility_assistance')->default(false);
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->boolean('suspended')->default(false);
            $table->timestamp('suspended_at')->nullable();
            $table->foreignId('suspended_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('suspended_reason')->nullable();
            $table->timestamp('unsuspended_at')->nullable();
            $table->foreignId('unsuspended_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
