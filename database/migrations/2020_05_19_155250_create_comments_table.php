<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->Integer('post_id')->unsigned();
            $table->bigInteger('user_id')->unsigned()->nullable() ;
            $table->bigInteger('parent_id')->unsigned()->nullable() ;
            $table->string('ip')->nullable() ;
            $table->text('comment')->nullable() ;
            $table->tinyInteger('status')->default(0) ;
            $table->timestamps();
        });

        Schema::table('comments', function(Blueprint $table) {
			        $table->foreign('post_id')->references('id')->on('posts')
					->onDelete('no action')
                    ->onUpdate('no action');
            
                    $table->foreign('user_id')->references('id')->on('users')
					->onDelete('no action')
                    ->onUpdate('no action');
                    /*
                    $table->foreign('parent_id')->references('id')->on('parent')
					->onDelete('no action')
                    ->onUpdate('no action');
                    */
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
