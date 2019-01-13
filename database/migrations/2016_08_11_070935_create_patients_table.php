<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',50);
            $table->string('gender',10);
            $table->bigInteger('sin')->nullable();
            $table->text('address');
            $table->date('birthdate');
			$table->string('social_status',15)->nullable();
			$table->integer('phone_num')->nullable();
			$table->string('nationality',20)->nullable();
			$table->string('job',50)->nullable();
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
        Schema::drop('patients');
    }
}
