<?php

use Illuminate\Database\Seeder;
use App\MedicalUnit;

class DepartmentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
		MedicalUnit::truncate();
		
    }
}
