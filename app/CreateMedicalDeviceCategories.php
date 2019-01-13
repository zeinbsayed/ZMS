<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CreateMedicalDeviceCategories extends Model
{
    //
    protected $fillable=[
        'name',
        'arabic_name'  
    ];

    public function types()
    {
        return $this->hasMany('App\MedicalDeviceType');
    }
}
