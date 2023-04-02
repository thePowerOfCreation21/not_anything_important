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
        Schema::create('report_card_exam_scores', function (Blueprint $table) {
            $table->id();
            $table->string('report_card_exam_id', 20);
            $table->string('student_id', 20);
            $table->float('score', 5, 2)->unsigned()->default(0);
            $table->boolean('is_present')->default(true);
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
        Schema::dropIfExists('report_card_exam_scores');
    }
};
