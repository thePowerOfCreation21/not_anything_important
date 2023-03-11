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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('full_name', 150);
            $table->string('father_name', 150)->nullable();
            $table->string('birth_certificate_number', 50)->nullable();
            $table->string('national_id', 50);
            $table->string('birth_certificate_location', 500)->nullable();
            $table->string('birth_location', 500)->nullable();
            $table->string('birth_date', 50)->nullable();
            $table->string('degree_of_education', 500)->nullable();
            $table->string('address', 500)->nullable();
            $table->string('phone_number', 50)->nullable();
            $table->string('file', 500)->nullable();
            $table->string('partner_full_name', 150)->nullable();
            $table->string('partner_job', 150)->nullable();
            $table->string('partner_birth_certificate_number', 50)->nullable();
            $table->string('partner_national_id', 50)->nullable();
            $table->string('partner_birth_certificate_location', 500)->nullable();
            $table->string('partner_birth_location', 500)->nullable();
            $table->string('partner_birth_date', 50)->nullable();
            $table->string('partner_degree_of_education', 150)->nullable();
            $table->string('partner_job_address', 500)->nullable();
            $table->string('partner_phone_number', 50)->nullable();
            $table->string('partner_emergency_call_number', 50)->nullable();
            $table->string('partner_file', 500)->nullable();
            $table->string('is_married', 50)->nullable();
            $table->string('password', 500);
            $table->string('register_status', 50);
            $table->timestamp('last_entrance_date')->nullable();
            $table->string('educational_year', 50);
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
        Schema::dropIfExists('teachers');
    }
};
