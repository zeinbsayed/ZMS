<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCancelledAttrVisitsTb extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
		Schema::table('visits', function (Blueprint $table) {
            //$table->integer('cancelled')->after('closed')->default(false);
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
		Schema::table('visits', function (Blueprint $table) {
            //$table->dropColumn('cancelled');
        });
    }
}
