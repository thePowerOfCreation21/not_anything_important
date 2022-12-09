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
        Schema::create('class_files', function (Blueprint $table) {
            $table->id();
            $table->string('author_type', 100);
            $table->bigInteger('author_id')->unsigned();
            $table->bigInteger('class_course_id')->unsigned()->nullable();
            $table->bigInteger('class_id')->unsigned()->nullable();
            $table->string('title', 250);
            $table->string('file', 500);
            $table->string('size', 100)->nullable();
            $table->string('educational_year', 50)->nullable();
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
        Schema::dropIfExists('class_files');
    }
};
