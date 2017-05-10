<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uet_questions', function (Blueprint $table) {
            $table->increments('id');
            $table->mediumText('content');
            $table->integer('test_id')->unsigned();
            $table->foreign('test_id')->references('id')->on('uet_tests')->onDelete('cascade');
            $table->integer('correct_answer');
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
        Schema::drop('uet_questions');
    }
}
