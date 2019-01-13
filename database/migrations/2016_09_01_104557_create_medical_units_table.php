<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\MedicalUnit;

class CreateMedicalUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medical_units', function (Blueprint $table) {
            $table->increments('id');
			$table->string('name');
			$table->string('type',1);
			$table->integer('parent_department_id')->nullable();
			$table->integer('free')->nullable();
            $table->timestamps();
        });
		
		Schema::create('medical_unit_visit', function (Blueprint $table) {
            $table->integer('visit_id');
            $table->integer('medical_unit_id');
			$table->integer('user_id');
			$table->integer('convert_to')->nullable();
			$table->boolean('department_conversion');
			$table->date('conversion_date');
			$table->integer('reference_doctor_id')->nullable();
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
		Schema::drop('medical_unit_visit');
        Schema::drop('medical_units');
    }
}
