<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('exams', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type')->default('test') ;
            $table->bigInteger('student_id')->unsigned()->nullable(); // Nullable for Mock test for all users
            $table->bigInteger('level_id')->unsigned()->nullable(); // Nullable for Mock test for all users
            $table->integer('questions_count')->default(10) ; // Can set by student if test  or admin if mock test
            $table->integer('available_time')->default(10) ; // Can set by student if test  or admin if mock test
            $table->timestamps();
            $table->foreign('student_id')->references('id')->on('users')->onDelete('set null')->onUpdate('no action');
            $table->foreign('level_id')->references('id')->on('taxonomies')->onDelete('set null')->onUpdate('no action');

        });
        Schema::create('exams_subjects', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('exam_id')->unsigned()->nullable();
            $table->bigInteger('subject_id')->unsigned()->nullable();
            $table->foreign('exam_id')->references('id')->on('exams')->onDelete('no action')->onUpdate('no action');
            $table->foreign('subject_id')->references('id')->on('taxonomies')->onDelete('no action')->onUpdate('no action');
        });
        
        Schema::create('exams_questions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('exam_id')->unsigned();
            $table->bigInteger('question_id')->unsigned();
            $table->foreign('exam_id')->references('id')->on('exams')->onDelete('no action')->onUpdate('no action');
            $table->foreign('question_id')->references('id')->on('questions')->onDelete('no action')->onUpdate('no action');

        });

        Schema::create('exams_answers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('exam_id')->unsigned();
            $table->bigInteger('student_id')->unsigned()->nullable(); // Nullable for Mock test for all users
            $table->bigInteger('question_id')->unsigned();
            $table->bigInteger('answer_id')->unsigned();
            $table->tinyInteger('is_true')->default(1);
            $table->smallInteger('spent_time')->default(0);  // Time in seconds
            $table->foreign('exam_id')->references('id')->on('exams')->onDelete('no action')->onUpdate('no action');
            $table->foreign('question_id')->references('id')->on('questions')->onDelete('no action')->onUpdate('no action');
            $table->foreign('answer_id')->references('id')->on('answers')->onDelete('no action')->onUpdate('no action');
            $table->foreign('student_id')->references('id')->on('users')->onDelete('no action')->onUpdate('no action');
        });

        Schema::create('exams_results', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('exam_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->smallInteger('valid_answers');
            $table->smallInteger('wrong_answers');
            $table->float('percent');
            $table->foreign('exam_id')->references('id')->on('exams')->onDelete('no action')->onUpdate('no action');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('no action')->onUpdate('no action');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exams');
        Schema::dropIfExists('exams_subjects');
        Schema::dropIfExists('exams_questions');
        Schema::dropIfExists('exams_answers');
        Schema::dropIfExists('exams_results');
    }
}
