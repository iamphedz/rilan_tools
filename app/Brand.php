<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Product;

class Brand extends Model
{
    protected $fillable = ['brand_name'];
    protected $appends = ['total_product'];


    public function getTotalProductAttribute() {
    	return Product::where('brand_id', $this->id)->get()->count();
    }
}
