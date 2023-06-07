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
        Schema::create('survey_categories', function (Blueprint $table) {
            $table->id();
            $table->text('text');
            $table->integer('participants_count')->unsigned()->default(0);
            $table->boolean('is_active')->default(false);
            $table->enum('type', ['teacher', 'student']);
            $table->string('educational_year', 20)->nullable();
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
        Schema::dropIfExists('survey_categories');
    }
};
