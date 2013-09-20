<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFileRequestsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('file_requests', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->increments('id');
			$table->timestamps();
			$table->softDeletes();

			$table->integer('user_id')->unsigned();
			$table->smallInteger('status');
			$table->string('title');
			$table->text('user_notes')->nullable;
			$table->text('admin_notes')->nullable;
			$table->integer('file_id')->unsigned()->nullable();
			$table->dateTime('fulfilled_on')->nullable();

			$table->index('user_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('file_requests');
	}

}
