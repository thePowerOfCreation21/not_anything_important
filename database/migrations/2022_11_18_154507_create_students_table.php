<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 150);
            $table->string('last_name', 150)->nullable();
            $table->string('full_name', 350)->nullable();
            $table->string('meli_code', 25);
            $table->string('birth_certificate_number', 25)->nullable();
            $table->string('birth_certificate_serie_number', 25)->nullable();
            $table->string('birth_certificate_serial_number', 25)->nullable();
            $table->text('birth_certificate_issued_location')->nullable();
            $table->text('birth_location')->nullable();
            $table->string('birth_date', 50)->nullable();
            $table->string('birth_place', 250)->nullable();
            $table->string('nationality', 150)->nullable();
            $table->string('religion', 150)->nullable();
            $table->string('religion_orientation', 150)->nullable();
            $table->text('illness_record')->nullable();
            $table->text('medicine_in_use')->nullable();
            $table->string('family_child_number', 100)->nullable();
            $table->string('all_family_children_count', 100)->nullable();
            $table->string('is_disabled', 150)->nullable();
            $table->string('divorced_parents', 150)->nullable();
            $table->string('dominant_hand', 150)->nullable();
            $table->string('living_with', 150)->nullable();
            $table->text('address')->nullable();
            $table->string('mobile_number', 50)->nullable();
            $table->string('phone_number', 50)->nullable();
            $table->string('phone_number_of_close_relative', 50)->nullable();
            $table->string('father_first_name', 150)->nullable();
            $table->string('father_last_name', 150)->nullable();
            $table->string('father_father_name', 150)->nullable();
            $table->string('father_birth_certificate_number', 25)->nullable();
            $table->string('father_birth_certificate_serie_number', 25)->nullable();
            $table->string('father_birth_certificate_serial_number', 25)->nullable();
            $table->text('father_birth_certificate_issued_location')->nullable();
            $table->text('father_birth_location')->nullable();
            $table->string('father_birth_date', 50)->nullable();
            $table->string('father_nationality', 150)->nullable();
            $table->string('father_religion', 150)->nullable();
            $table->string('father_religion_orientation', 150)->nullable();
            $table->string('father_meli_code', 25)->nullable();
            $table->string('father_education', 100)->nullable();
            $table->string('father_job', 150)->nullable();
            $table->text('father_health_status')->nullable();
            $table->string('father_mobile_number', 50)->nullable();
            $table->text('father_work_address')->nullable();
            $table->string('father_file', 500)->nullable();
            $table->string('father_educational_level', 150)->nullable();
            $table->string('mother_first_name', 150)->nullable();
            $table->string('mother_last_name', 150)->nullable();
            $table->string('mother_father_name', 150)->nullable();
            $table->string('mother_birth_certificate_number', 25)->nullable();
            $table->string('mother_birth_certificate_serie_number', 25)->nullable();
            $table->string('mother_birth_certificate_serial_number', 25)->nullable();
            $table->string('mother_birth_certificate_issued_location', 1500)->nullable();
            $table->text('mother_birth_location')->nullable();
            $table->string('mother_birth_date', 50)->nullable();
            $table->string('mother_nationality', 150)->nullable();
            $table->string('mother_religion', 150)->nullable();
            $table->string('mother_religion_orientation', 150)->nullable();
            $table->string('mother_meli_code', 25)->nullable();
            $table->string('mother_education', 100)->nullable();
            $table->string('mother_job', 150)->nullable();
            $table->text('mother_health_status')->nullable();
            $table->string('mother_mobile_number', 25)->nullable();
            $table->text('mother_work_address')->nullable();
            $table->string('mother_file', 500)->nullable();
            $table->string('mother_educational_level', 150)->nullable();
            $table->text('non_contagious_illness')->nullable();
            $table->text('mental_illness')->nullable();
            $table->string('level', 100)->nullable();
            $table->string('file', 500)->nullable();
            $table->string('report_card_pdf', 500)->nullable();
            $table->string('father_birth_place', 250)->nullable();
            $table->string('mother_birth_place', 250)->nullable();
            $table->string('educational_year', 25);
            $table->integer('wallet_amount')->default(0);
            $table->boolean('is_block')->default(false);
            $table->text('reason_for_blocking')->nullable();
            $table->boolean('should_change_password')->default(false);
            $table->string('password', 150);
            $table->string('register_status', 50);
            $table->timestamp('last_time_sms_sent')->nullable();
            $table->bigInteger('otp_expires_at')->default(0);
            $table->string('otp', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('students');
    }
};
