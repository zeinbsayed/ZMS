<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExitUserIdToVisitTb extends Migration
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
			$table->integer('exit_user_id')->nullable()->after('exit_date');
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
			$table->dropColumn('exit_user_id');
        });
    }
}
