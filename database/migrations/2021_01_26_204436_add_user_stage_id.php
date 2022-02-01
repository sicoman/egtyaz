<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserStageId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::table('users', function($table){
         $table->bigInteger('stage_id')->unsigned()->nullable();
         $table->dropColumn(['mar7la']);
         $table->index(['stage_id']);
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::table('users', function($table){
            $table->bigInteger('mar7la')->unsigned()->nullable();
            $table->dropColumn(['stage_id']);
       });
    }
}
