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
            $table->string('payment_type', 50);
            $table->string('check_number', 50)->nullable();
            $table->string('receipt_number', 50)->nullable();
            $table->integer('amount');
            $table->boolean('paid')->default(false);
            $table->timestamp('date');
            $table->timestamp('payment_date')->nullable();
            $table->string('educational_year', 50);
            $table->string('check_image', 500)->nullable();
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
