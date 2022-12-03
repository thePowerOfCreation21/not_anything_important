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
        Schema::create('class_message_student', function (Blueprint $table) {
            $table->id();
            $table->string('student_id', 20);
            $table->bigInteger('class_message_id')->unsigned();
            $table->foreign('class_message_id')
                ->references('id')
                ->on('class_messages')
                ->onDelete('cascade');
            $table->boolean('is_seen')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('class_message_student');
    }
};
