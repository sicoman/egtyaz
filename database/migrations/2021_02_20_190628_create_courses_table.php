<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->bigInteger('taxonomy_id')->unsigned()->nullable();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->text('description')->nullable();
            $table->float('price')->default('0') ;
            $table->string('photo')->nullable();
            $table->string('file')->nullable();
            $table->tinyInteger('status')->default(1) ;
            $table->timestamps();
        });

        Schema::create('course_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('course_id')->unsigned()->nullable();
            $table->string('title') ;
            $table->string('file')->nullable() ;
            $table->string('time')->nullable() ;
            $table->tinyInteger('status')->default(0) ;
            $table->timestamps();
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->string('type' , 50)->default('package')->after('gateway') ;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('courses');
    }
}
