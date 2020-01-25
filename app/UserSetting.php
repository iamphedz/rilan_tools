<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model
{
    protected $fillable = ['user_id','product'];
    protected $casts = [
        "product"    =>  'array'
    ];

}
