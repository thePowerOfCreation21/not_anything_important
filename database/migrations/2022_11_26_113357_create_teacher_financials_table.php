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
        Schema::create('teacher_financials', function (Blueprint $table) {
            $table->id()->startingValue(1000);
            $table->string('teacher_id', 20);
            $table->integer('amount');
            $table->timestamp('date');
            $table->string('educational_year', 50);
            $table->string('receipt_image', 500)->nullable();
            $table->string('description', 2500)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teacher_financials');
    }
};
