<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChallengersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('challenges')) {
            Schema::create('challenges', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->bigInteger('exam_id')->unsigned();
                $table->bigInteger('user_id')->unsigned()->nullable(); // Challenger Person
                $table->timestamps();
                $table->foreign('exam_id')->references('id')->on('exams')->onDelete('no action')->onUpdate('no action');
            });
        }

        if (!Schema::hasTable('challengers')) {
            Schema::create('challengers', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->bigInteger('challenge_id')->unsigned();
                $table->bigInteger('user_id')->unsigned();
                $table->tinyInteger('status')->default(0);  // 0   1   -1  
                $table->foreign('challenge_id')->references('id')->on('challenges')->onDelete('no action')->onUpdate('no action');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('no action')->onUpdate('no action');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('challenges');
        Schema::dropIfExists('challengers');
    }
}
