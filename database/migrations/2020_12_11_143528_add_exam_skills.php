<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExamSkills extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exams_skills', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('exam_id')->unsigned()->nullable();
            $table->bigInteger('skill_id')->unsigned()->nullable();
            $table->foreign('exam_id')->references('id')->on('exams')->onDelete('no action')->onUpdate('no action');
            $table->foreign('skill_id')->references('id')->on('taxonomies')->onDelete('no action')->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exams_skills') ;
    }
}
