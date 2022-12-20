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
        Schema::create('inventory_product_histories', function (Blueprint $table) {
            $table->id();
            $table->string('action', 25);
            $table->integer('amount');
            $table->timestamp('date');
            $table->bigInteger('inventory_product_id')->unsigned();
            $table->foreign('inventory_product_id')->references('id')->on('inventory_products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_product_histories');
    }
};
