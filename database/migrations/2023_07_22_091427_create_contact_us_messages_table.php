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
        Schema::create('contact_us_messages', function (Blueprint $table) {
            $table->id();
            $table->string('full_name', 150);
            $table->string('phone_number', 20);
            $table->string('email', 150)->nullable();
            $table->string('text', 5000)->nullable();
            $table->boolean('is_seen')->default(false);
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
        Schema::dropIfExists('contact_us_messages');
    }
};
