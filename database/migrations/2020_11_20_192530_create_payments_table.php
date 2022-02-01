<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
	{
		Schema::create('payments', function(Blueprint $table) {
			$table->increments('id');
			$table->bigInteger('package_id')->unsigned()->index();
			$table->bigInteger('user_id')->unsigned()->index();
			$table->integer('cost');
			$table->smallInteger('fee')->index()->default('0');
			$table->integer('total')->index();
			$table->tinyInteger('is_paid')->index();
			$table->smallInteger('status')->index();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('payments');
	}
}
