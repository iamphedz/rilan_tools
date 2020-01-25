<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ["order_request_id", "payment_details"];
    protected $cast = [
        'order_request_id'  =>  'integer'
    ];

    public function order_request()
    {
        return $this->hasOne('App\OrderRequest', 'id', 'order_request_id');
    }

    public function image()
    {
        return $this->morphOne('App\Image', 'imageable');
    }
}
