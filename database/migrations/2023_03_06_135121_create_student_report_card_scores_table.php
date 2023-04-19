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
        Schema::create('student_report_card_scores', function (Blueprint $table) {
            $table->id();
            $table->float('score', 6, 2)->unsigned();
            $table->tinyInteger('highest_score_in_class')->unsigned()->default(0);
            $table->integer('rank_in_class')->unsigned()->default(0);
            $table->integer('rank_in_level')->unsigned()->default(0);
            $table->boolean('has_star')->default(false);
            $table->string('student_report_card_id', 20);
            $table->string('course_id', 20);
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
        Schema::dropIfExists('student_report_card_scores');
    }
};
