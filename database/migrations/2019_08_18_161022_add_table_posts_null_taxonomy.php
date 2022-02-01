<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTablePostsNullTaxonomy extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('posts' , function(BluePrint $table){
            $table->bigInteger('taxonomy_id')->unsigned()->nullable()->change();
            $table->text('description')->nullable()->change();
            $table->smallInteger('status')->default(0)->change();
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
