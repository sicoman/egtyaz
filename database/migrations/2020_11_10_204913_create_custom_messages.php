<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomMessages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type')->default('["email"]') ;
            $table->string('title')->nullable() ;
            $table->text('description')->nullable() ;
            $table->timestamps();
        });
        Schema::create('custom_message_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned() ;
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
        Schema::dropIfExists('custom_messages');
        Schema::dropIfExists('custom_message_users');
    }
}
