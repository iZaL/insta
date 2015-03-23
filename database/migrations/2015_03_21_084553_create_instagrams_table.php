<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstagramsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('instagrams', function(Blueprint $table)
		{
            $table->increments('id');
            $table->integer('client_id');
            $table->integer('access_token');
            $table->string('username');
            $table->string('fullname');
            $table->softDeletes();
            $table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('instagrams', function(Blueprint $table)
		{
            $table->drop();
		});
	}

}
