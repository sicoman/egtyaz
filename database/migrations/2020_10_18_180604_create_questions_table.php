<?php

use Illuminate\Support\Facades\Schema;
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
        Schema::create('questions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('category_id')->unsigned()->nullable() ;
            $table->bigInteger('subject_id')->unsigned()->nullable() ;
            $table->bigInteger('skill_id')->unsigned()->nullable() ;
            $table->text('title') ;
            $table->bigInteger('level_id')->unsigned()->nullable() ;
            $table->tinyInteger('status')->default(1) ;  // [ 0 -> disabled , 1 -> enabled ]
            $table->timestamps();
            $table->foreign('category_id')->references('id')->on('taxonomies')->onDelete('set null')->onUpdate('no action');
            $table->foreign('subject_id')->references('id')->on('taxonomies')->onDelete('set null')->onUpdate('no action');
            $table->foreign('skill_id')->references('id')->on('taxonomies')->onDelete('set null')->onUpdate('no action');
            $table->foreign('level_id')->references('id')->on('taxonomies')->onDelete('set null')->onUpdate('no action');
        });

        Schema::create('answers', function(Blueprint $table) {
             $table->bigIncrements('id');
             $table->bigInteger('question_id')->unsigned()->nullable() ;
             $table->text('text')->nullable() ;
             $table->tinyInteger('is_true')->default(0) ;
             $table->tinyInteger('status')->default(1) ;
             $table->foreign('question_id')->references('id')->on('questions')->onDelete('set null')->onUpdate('no action');
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
        Schema::dropIfExists('answers');
    }
}
