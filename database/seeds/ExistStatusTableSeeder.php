<?php

use Illuminate\Database\Seeder;
use App\ExistStatus;

class ExistStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
		ExistStatus::truncate();
		ExistStatus::create(['name'=>'شفاء']);
		ExistStatus::create(['name'=>'تحسن']);
		ExistStatus::create(['name'=>'لم يتغيير']);
		ExistStatus::create(['name'=>'تدهور']);
		ExistStatus::create(['name'=>'وفاة']);
		ExistStatus::create(['name'=>'هروب']);
		ExistStatus::create(['name'=>'خروج حسب الطلب']);
		ExistStatus::create(['name'=>'اخرى']);

    }
}
