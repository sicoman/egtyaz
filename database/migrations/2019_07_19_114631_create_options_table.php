<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOptionsTable extends Migration {

	public function up()
	{
		Schema::create('options', function(Blueprint $table) {
			$table->increments('id');
			$table->string('type')->index();
			$table->string('option_var')->index();
			$table->mediumText('option_value')->nullable();
			$table->string('html')->index()->default('text');
			$table->tinyInteger('status')->index()->default('1');
			$table->string('description')->nullable();
			$table->string('description_en')->nullable();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('options');
	}
}