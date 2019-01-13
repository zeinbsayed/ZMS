<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Entrypoint;

class CreateEntrypointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entrypoints', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
			$table->string('location');
			$table->text('type');
			$table->timestamps();
        });
		Schema::create('entrypoint_user', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->index();
            $table->integer('entrypoint_id')->unsigned()->index();
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
		
		Schema::drop('entrypoint_user');
        Schema::drop('entrypoints');
      
    }
}
