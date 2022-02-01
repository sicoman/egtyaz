<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Model;

class CreateForeignKeys extends Migration {

	public function up()
	{
		
		Schema::table('taxonomies', function(Blueprint $table) {
			$table->foreign('parent')->references('id')->on('taxonomies')
						->onDelete('no action')
						->onUpdate('no action');
		});
		
		Schema::table('badges', function(Blueprint $table) {
			$table->foreign('user_id')->references('id')->on('users')
						->onDelete('no action')
						->onUpdate('no action');
		});
		
		Schema::table('posts', function(Blueprint $table) {
			$table->foreign('user_id')->references('id')->on('users')
						->onDelete('no action')
						->onUpdate('no action');
		});

		Schema::table('posts', function(Blueprint $table) {
			$table->foreign('taxonomy_id')->references('id')->on('taxonomies')
						->onDelete('no action')
						->onUpdate('no action');
		});
		
	}

	public function down()
	{
		
		Schema::table('taxonomies', function(Blueprint $table) {
			$table->dropForeign('taxonomies_parent_foreign');
		});
		
		
		Schema::table('badges', function(Blueprint $table) {
			$table->dropForeign('badges_user_id_foreign');
		});
		
		Schema::table('posts', function(Blueprint $table) {
			$table->dropForeign('posts_user_id_foreign');
		});
		Schema::table('posts', function(Blueprint $table) {
			$table->dropForeign('posts_taxonomy_id_foreign');
		});
	
	}
}