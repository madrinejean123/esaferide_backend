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
        Schema::table('drivers', function (Blueprint $table) {
            $table->string('first_name')->nullable()->after('full_name');
            $table->string('last_name')->nullable()->after('first_name');
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('license_no')->nullable();
            $table->date('license_expiry_date')->nullable();
            $table->date('psv_insurance_expiry_date')->nullable();
            $table->string('national_id_number')->nullable();
            $table->string('vehicle_make_model')->nullable();
            $table->string('vehicle_reg_no')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->string('driver_license_url')->nullable();
            $table->string('psv_insurance_sticker_url')->nullable();
            $table->string('national_id_url')->nullable();
            $table->string('passport_photo_url')->nullable();
            $table->boolean('has_good_conduct_certificate')->default(false);
            $table->text('notes')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('rejected_at')->nullable();
            $table->foreignId('rejected_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('suspended_at')->nullable();
            $table->foreignId('suspended_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('suspended_reason')->nullable();
            $table->timestamp('unsuspended_at')->nullable();
            $table->foreignId('unsuspended_by')->nullable()->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('drivers', function (Blueprint $table) {
            $table->dropForeign(['verified_by']);
            $table->dropForeign(['rejected_by']);
            $table->dropForeign(['suspended_by']);
            $table->dropForeign(['unsuspended_by']);
            $table->dropColumn([
                'first_name', 'last_name', 'phone', 'address', 'license_no',
                'license_expiry_date', 'psv_insurance_expiry_date', 'national_id_number',
                'vehicle_make_model', 'vehicle_reg_no', 'emergency_contact_name',
                'emergency_contact_phone', 'driver_license_url', 'psv_insurance_sticker_url',
                'national_id_url', 'passport_photo_url', 'has_good_conduct_certificate',
                'notes', 'rejection_reason', 'verified_at', 'verified_by', 'rejected_at',
                'rejected_by', 'suspended_at', 'suspended_by', 'suspended_reason',
                'unsuspended_at', 'unsuspended_by',
            ]);
        });
    }
};
