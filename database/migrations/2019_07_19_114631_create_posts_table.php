<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePostsTable extends Migration {

	public function up()
	{
		Schema::create('posts', function(Blueprint $table) {
			$table->increments('id');
			$table->string('type')->index();
			$table->bigInteger('user_id')->unsigned()->index()->default('1');
			$table->string('title');
			$table->mediumText('description');
			$table->string('photo')->nullable();
			$table->bigInteger('taxonomy_id')->unsigned()->index();
			$table->tinyInteger('status');
			$table->integer('views')->default('0');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('posts');
	}
}