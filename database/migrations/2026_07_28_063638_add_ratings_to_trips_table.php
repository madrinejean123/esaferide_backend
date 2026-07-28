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
        Schema::table('trips', function (Blueprint $table) {
            $table->unsignedTinyInteger('student_rating_stars')->nullable();
            $table->text('student_rating_comment')->nullable();
            $table->timestamp('student_rated_at')->nullable();
            $table->unsignedTinyInteger('driver_rating_stars')->nullable();
            $table->text('driver_rating_comment')->nullable();
            $table->timestamp('driver_rated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trips', function (Blueprint $table) {
            $table->dropColumn([
                'student_rating_stars', 'student_rating_comment', 'student_rated_at',
                'driver_rating_stars', 'driver_rating_comment', 'driver_rated_at',
            ]);
        });
    }
};
