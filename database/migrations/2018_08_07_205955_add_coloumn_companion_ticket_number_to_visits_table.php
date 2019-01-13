<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColoumnCompanionTicketNumberToVisitsTable extends Migration
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
            //
			 $table->integer('Companion_Ticket_Number')->nullable();
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
		$table->dropColumn('Companion_Ticket_Number');
		});
    }
}
