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
        Schema::create('student_financials', function (Blueprint $table) {
            $table->id()->startingValue(1000);
            $table->string('student_id', 20);
            $table->integer('amount');
            $table->boolean('paid')->default(false);
            $table->timestamp('date');
            $table->string('educational_year', 50);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_financials');
    }
};
