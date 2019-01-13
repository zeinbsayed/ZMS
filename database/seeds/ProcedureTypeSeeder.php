<?php

use Illuminate\Database\Seeder;
use App\ProcedureType;

class ProcedureTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
		ProcedureType::truncate();
		ProcedureType::create(['name'=>'Radiology']);
		ProcedureType::create(['name'=>'Lab']);
    }
}
