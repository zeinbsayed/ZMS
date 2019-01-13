<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Wsconfig;
class CreateWebServiceConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wsconfig', function (Blueprint $table) {
            $table->increments('id');
            $table->text('url');
            $table->string('sending_app',30);
            $table->string('sending_fac',30);
            $table->string('receiving_app',30);
            $table->string('receiving_fac',30);
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
        Schema::drop('wsconfig');
    }
}
