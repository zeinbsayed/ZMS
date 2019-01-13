<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColoumnReferenceDoctorIdToMedicalUnitVisitTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('medical_unit_visit', function (Blueprint $table) {
            //
			 $table->integer('reference_doctor_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('medical_unit_visit', function (Blueprint $table) {
            //
        });
    }
}
