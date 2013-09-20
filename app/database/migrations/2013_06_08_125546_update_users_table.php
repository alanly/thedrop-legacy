<?php

use Illuminate\Database\Migrations\Migration;

class UpdateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('users', function($table)
        {
            // Add the necessary columns and database properties.
            $table->softDeletes();
            $table->string('name');
            $table->string('api_key')->nullable();

            // Drop the un-needed columns.
            $table->dropColumn('first_name', 'last_name');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
    {
        // Do nothing; handled by Sentry migrations.
	}

}
