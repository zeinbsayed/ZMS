<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnNewIdToPatients extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('patients', function (Blueprint $table) {
            //
			$table->string('new_id',50)->nullable();
         });
        }
    /**
     * Reverse the migrations.
     *
     * @return void
     */

    public function down()
    {
        //
		 Schema::table('patients', function (Blueprint $table) {
            //
			$table->dropColumn('new_id');
        });
    }
}
