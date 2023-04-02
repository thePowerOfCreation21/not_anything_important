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
        Schema::create('student_report_cards', function (Blueprint $table) {
            $table->id();
            $table->string('title', 250);
            $table->string('month', 100)->nullable();
            $table->string('educational_year', 100);
            $table->float('total_score')->unsigned();
            $table->integer('total_ratio')->unsigned();
            $table->integer('rank_in_class')->unsigned()->default(0);
            $table->integer('rank_in_level')->unsigned()->default(0);
            $table->string('report_card_id', 20);
            $table->string('class_id', 20);
            $table->string('student_id', 20);
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
        Schema::dropIfExists('student_report_cards');
    }
};
