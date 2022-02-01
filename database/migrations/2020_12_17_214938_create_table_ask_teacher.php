<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableAskTeacher extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ask_teachers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned() ;
            $table->text('question') ;
            $table->bigInteger('subject_id')->unsigned();
            $table->bigInteger('skill_id')->unsigned();
            $table->timestamps();
        });

        Schema::create('ask_answers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('ask_id')->unsigned() ;
            $table->text('answer') ;
            $table->bigInteger('teacher_id')->unsigned();
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
        Schema::dropIfExists('ask_teacher');
        Schema::dropIfExists('ask_answers');
    }
}
