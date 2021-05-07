<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->string('questions_data');
            $table->bigInteger('a_id')->unsigned()->nullable()->comment('รหัสการจอง');
            $table->foreign('a_id')->references('id')->on('answers')->onDelete('cascade');
            $table->bigInteger('c_id')->unsigned()->nullable()->comment('รหัสการจอง');
            $table->foreign('c_id')->references('id')->on('categories')->onDelete('cascade');
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
        Schema::dropIfExists('questions');
    }
}
