<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColoumnOutPatientToVisitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('visits', function (Blueprint $table) {
            //
			 $table->integer('out_patient')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('visits', function (Blueprint $table) {
            //
			$table->dropColumn('out_patient');
        });
    }
}
