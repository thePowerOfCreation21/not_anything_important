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
        Schema::create('food_date_food_pivot', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('food_date_id')->unsigned();
            $table->foreign('food_date_id')->references('id')->on('food_dates')->onDelete('cascade');
            $table->bigInteger('food_id')->unsigned();
            $table->foreign('food_id')->references('id')->on('foods')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('date_food_pivot');
    }
};
