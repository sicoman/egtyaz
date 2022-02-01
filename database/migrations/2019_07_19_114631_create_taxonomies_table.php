<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTaxonomiesTable extends Migration {

	public function up()
	{
		Schema::create('taxonomies', function(Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('type')->index()->default('category');
			$table->string('name');
			$table->text('description')->nullable();
			$table->string('name_en')->index();
			$table->text('description_en')->nullable();
			$table->string('photo')->nullable();
			$table->bigInteger('parent')->unsigned()->nullable();
			$table->tinyInteger('status')->index()->default('1');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('taxonomies');
	}
}