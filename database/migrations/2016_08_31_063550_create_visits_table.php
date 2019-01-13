<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVisitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visits', function (Blueprint $table) {
		
            $table->increments('id');
            $table->integer('patient_id');
			$table->integer('serial_number')->nullable();
			$table->string('ticket_number', 20)->nullable();
			$table->string('ticket_type',20)->nullable();
			$table->string('ticket_status',1)->nullable();
            $table->string('watching_status')->nullable();
            $table->string('sent_by_person')->nullable();
			$table->string('ticket_companion_name',50)->nullable();
            $table->bigInteger('ticket_companion_sin')->nullable();

			$table->string('companion_name')->nullable();
			$table->bigInteger('companion_sid')->nullable();
			$table->string('companion_address')->nullable();
			$table->string('companion_job')->nullable();
			$table->string('companion_phone_num')->nullable();
			
			$table->integer('person_relation_id')->nullable();
			$table->string('person_relation_name')->nullable();
			$table->string('person_relation_phone_num')->nullable();
			$table->string('person_relation_address')->nullable();
			
			$table->text('entry_reason_desc')->nullable();
			$table->date('entry_date')->nullable();
			$table->string('entry_time',10)->nullable();
			$table->string('room_number',50)->nullable();
			$table->string('file_number',50)->nullable();
			$table->integer('file_type')->nullable();
			$table->integer('cure_type_id')->nullable();
			$table->integer('contract_id')->nullable();
			
			//$table->integer('reference_doctor_id')->nullable();
			$table->date('exit_date')->nullable();
			
			$table->integer('exit_status_id')->nullable();
			$table->text('final_diagnosis')->nullable();
			$table->text('doctor_recommendation')->nullable();
			
			$table->integer('converted_from')->nullable();
			$table->text('summary')->nullable();
			$table->boolean('all_deps')->default(false);
			$table->boolean('closed')->default(false);
			$table->boolean('cancelled')->default(false);
			$table->integer('entry_id');
			$table->integer('user_id');
			
			$table->integer('convert_to_entry_id')->nullable();
			$table->integer('convert_to_user_id')->nullable();
			$table->integer('converted_by')->nullable();

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
        Schema::drop('visits');
    }
}
