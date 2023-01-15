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
        Schema::create('advices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('advice_date_id')->constrained('advice_dates')->onDelete('cascade');
            $table->foreignId('advice_hour_id')->constrained('advice_hours')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->string('status', 255)->default('pending');
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
        Schema::dropIfExists('advices');
    }
};
