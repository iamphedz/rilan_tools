<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = ["category_id", "brand_id", "product_images", "product_name", "description", "price", "qty"];
    protected $appends = ["image_paths","has_images"];

    protected $default_product_images = [
        '/images/products/default.jpg'
    ];

    public function getImagePathsAttribute()
    {
        if ($this->images()->count() > 0)
            return collect($this->images()->pluck('path'));
        else
            return $this->default_product_images;
    }

    public function getHasImagesAttribute()
    {
        return $this->images()->count() > 0 ? true : false;
    }

    public function path($section = null)
    {
        switch ($section) {
            case "product":
                return url("/products/view/{$this->id}-" . Str::slug($this->product_name));
                break;
            case "brand":
                return $this->brand_id ? url("/products/brand/{$this->brand_id}-" . Str::slug($this->brand->brand_name)) : "";
                break;
            case "category":
                return url("/products/category/{$this->category_id}-" . Str::slug($this->category->category_name));
                break;
            default:
                return url("/products/view/{$this->id}-" . Str::slug($this->product_name));
                break;
        }
    }

    public function default_image_path()
    {
        return "/images/products/default.jpg";
    }

    /**
     * Get the product's image.
     */
    public function images()
    {
        return $this->morphMany('App\Image', 'imageable');
    }

    public function brand()
    {
        return $this->hasOne('App\Brand', 'id', 'brand_id');
    }

    public function category()
    {
        return $this->hasOne('App\Product_Category', 'id', 'category_id');
    }
}
