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
        Schema::create('class_score', function (Blueprint $table) {
            $table->id();
            $table->string('class_course_id', 20);
            $table->timestamp('date');
            $table->string('educational_year', 50)->nullable();
            $table->decimal('max_score', 5, 2);
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
        Schema::dropIfExists('class_score');
    }
};
