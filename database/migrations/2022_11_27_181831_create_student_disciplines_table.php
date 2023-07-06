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
        Schema::create('student_disciplines', function (Blueprint $table) {
            $table->id()->startingValue(1000);
            $table->string('student_id', '20');
            $table->string('title', '250');
            $table->string('description', '500');
            $table->timestamp('date');
            $table->string('educational_year', 50);
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
        Schema::dropIfExists('student_disciplines');
    }
};
