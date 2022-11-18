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
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('full_name')->nullable();
            $table->string('birth_certificate_number')->nullable();
            $table->string('birth_certificate_serie_number')->nullable();
            $table->string('birth_certificate_issued_location')->nullable();
            $table->string('birth_certificate_issued_location')->nullable();
            $table->string('birth_loc')->nullable();
            $table->string('birth_date')->nullable();
            $table->string('nationality')->nullable();
            $table->string('religion')->nullable();
            $table->string('religion_orientation')->nullable();
            $table->string('illness_record')->nullable();
            $table->string('medicine_in_us')->nullable();
            $table->string('family_child_number')->nullable();
            $table->string('all_family_children_count')->nullable();
            $table->string('is_disabled')->nullable();
            $table->string('divorced_parents')->nullable();
            $table->string('dominant_hand')->nullable();
            $table->string('living_with')->nullable();
            $table->string('address')->nullable();
            $table->string('mobile_number')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('father_first_name')->nullable();
            $table->string('father_last_name')->nullable();
            $table->string('father_father_name')->nullable();
            $table->string('father_birth_certificate_number')->nullable();
            $table->string('father_birth_certificate_serie_number')->nullable();
            $table->string('father_birth_certificate_issued_location')->nullable();
            $table->string('father_birth_location')->nullable();
            $table->string('father_birth_date')->nullable();
            $table->string('father_nationality')->nullable();
            $table->string('father_religion')->nullable();
            $table->string('father_religion_orientation')->nullable();
            $table->string('father_meli_code')->nullable();
            $table->string('father_education')->nullable();
            $table->string('father_job')->nullable();
            $table->string('father_health_status')->nullable();
            $table->string('father_mobile_number')->nullable();
            $table->string('father_work_address')->nullable();
            $table->string('mother_first_name')->nullable();
            $table->string('mother_last_name')->nullable();
            $table->string('mother_father_name')->nullable();
            $table->string('mother_birth_certificate_number')->nullable();
            $table->string('mother_birth_certificate_serie_number')->nullable();
            $table->string('mother_birth_certificate_issued_location')->nullable();
            $table->string('mother_birth_location')->nullable();
            $table->string('mother_birth_date')->nullable();
            $table->string('mother_nationality')->nullable();
            $table->string('mother_religion')->nullable();
            $table->string('mother_religion_orientation')->nullable();
            $table->string('mother_meli_code')->nullable();
            $table->string('mother_education')->nullable();
            $table->string('mother_job')->nullable();
            $table->string('mother_health_status')->nullable();
            $table->string('mother_mobile_number')->nullable();
            $table->string('mother_work_address')->nullable();
            $table->string('non_contagious_illness')->nullable();
            $table->string('mental_illness')->nullable();
            $table->string('level')->nullable();
            $table->string('file')->nullable();
            $table->string('report_card_pdf')->nullable();
            $table->string('educational_year');
            $table->integer('wallet_amount')->default(0);
            $table->boolean('is_block')->default(false);
            $table->boolean('should_change_password')->default(false);
            $table->string('password');
            $table->string('reason_for_blocking')->nullable();
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
