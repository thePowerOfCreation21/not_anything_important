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
        Schema::create('teacher_work_experiences', function (Blueprint $table) {
            $table->id();
            $table->string('teacher_id', 20);
            $table->string('title', 250);
            $table->string('workplace_name', 250);
            $table->string('work_title', 250);
            $table->string('current_status', 250);
            $table->string('reason_for_the_termination_of_cooperation', 250);
            $table->string('workplace_location', 250);

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
        Schema::dropIfExists('teacher_work_experiences');
    }
};
