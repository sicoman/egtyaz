<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable() ;
            $table->integer('period')->default(365) ; // Period Per Day
            $table->text('description')->nullable() ;
            $table->Integer('price')->default(1) ;
            $table->string('sale_type')->nullable() ;
            $table->float('sale_amount')->nullable() ;
            $table->TinyInteger('status')->default(1) ;
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
        Schema::dropIfExists('packages');
    }
}
