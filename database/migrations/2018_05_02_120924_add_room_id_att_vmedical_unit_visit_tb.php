<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRoomIdAttVmedicalUnitVisitTb extends Migration
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
			$table->integer('room_id')->nullable()->after('medical_unit_id');
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
