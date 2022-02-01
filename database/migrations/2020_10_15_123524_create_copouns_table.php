<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCopounsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('copouns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code')->nullable() ;
            $table->text('description')->nullable() ;
            $table->string('type')->default('percent') ;    //   percent or fixed
            $table->Integer('amount')->default(1) ;
            $table->TinyInteger('status')->default(1) ;   
            $table->Integer('times')->default(0) ;          // max used times
            $table->date('expire_date')->nullable() ;
            $table->TinyInteger('finished')->default(0) ;   // 1 = finished & 0 = Not yet
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
        Schema::dropIfExists('copouns');
    }
}
