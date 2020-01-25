<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Product;

class Product_Category extends Model
{
    protected $table = "product_category";
    protected $fillable = ["category_name"];
    protected $appends = ['total_product'];


    public function getTotalProductAttribute() {
    	return Product::where('category_id', $this->id)->get()->count();
    }
}
