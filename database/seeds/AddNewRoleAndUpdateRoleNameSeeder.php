<?php

use Illuminate\Database\Seeder;
use App\Role;

class AddNewRoleAndUpdateRoleNameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
		Role::create(['name'=>'Admin']);
		Role::create(['name'=>'Doctor','arabic_name'=>'الطبيب']);
		Role::create(['name'=>'Nursing','arabic_name'=>'التمريض']);
		Role::create(['name'=>'Entrypoint','arabic_name'=>'موظف مكتب دخول']);
		Role::create(['name'=>'Receiption','arabic_name'=>'موظف مكتب حجز تذاكر العيادات']);
		Role::create(['name'=>'SubAdmin','arabic_name'=>'مسئول عن النظام']);
		Role::create(['name'=>'Desk','arabic_name'=>'موظف مكتب استقبال']);		
    }
}
