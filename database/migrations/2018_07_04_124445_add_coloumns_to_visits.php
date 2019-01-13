<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColoumnsToVisits extends Migration
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
			$table->string('ambulance_number',10)->nullable();
			$table->string('paramedic_name',30)->nullable();
			$table->string('kateb_name',30)->nullable();
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
			$table->dropColumn('ambulance_number');
			$table->dropColumn('paramedic_name');
			$table->dropColumn('kateb_name');
        });
    }
}
