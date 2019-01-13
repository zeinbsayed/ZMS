<?php

use Illuminate\Database\Seeder;
use App\Contract;

class ContractsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
		Contract::truncate();
		Contract::create(['name'=>'التأمين الصحي']);
    }
}
