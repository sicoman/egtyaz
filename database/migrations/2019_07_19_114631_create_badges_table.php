<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBadgesTable extends Migration {

	public function up()
	{
		Schema::create('badges', function(Blueprint $table) {
			$table->increments('id');
			$table->bigInteger('user_id')->unsigned()->index();
			$table->bigInteger('badge')->unsigned()->index();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('badges');
	}
}