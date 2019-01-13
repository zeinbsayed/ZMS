<?php

use Illuminate\Database\Seeder;
use App\ConvertedFrom;


class ConvertedFromTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
		ConvertedFrom::create(['name'=>'استقبال']);
		ConvertedFrom::create(['name'=>'عيادات']);
		ConvertedFrom::create(['name'=>'اخرى']);
    }
}
