<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVisitDiagnosesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visit_diagnoses', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('visit_id');
			$table->text('content')->nullable();
			$table->text('content_in_english')->nullable();
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
        Schema::drop('visit_diagnoses');
    }
}
