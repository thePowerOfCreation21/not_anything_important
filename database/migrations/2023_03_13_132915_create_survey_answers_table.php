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
        Schema::create('survey_answers', function (Blueprint $table) {
            $table->id();
            $table->string('student_id', 20);
            $table->bigInteger('survey_option_id')->unsigned();
            // $table->foreign('survey_option_id')->references('id')->on('survey_options')->onDelete('cascade');
            $table->bigInteger('survey_id')->unsigned();
            // $table->foreign('survey_id')->references('id')->on('surveys')->onDelete('cascade');
            $table->bigInteger('survey_category_id')->unsigned();
            // $table->foreign('survey_category_id')->references('id')->on('survey_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('survey_answers');
    }
};
