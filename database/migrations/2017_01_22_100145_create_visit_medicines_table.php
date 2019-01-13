<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVisitMedicinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visit_medicines', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('visit_id');
			$table->text('name');
			$table->text('accessories')->nullable();
			$table->integer('typist_id');
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
        Schema::drop('visit_medicines');
    }
}
