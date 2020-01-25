<?php

namespace App\Repositories;

use App\Http\CustomClass\Image;
use App\Product;
use Illuminate\Support\Str;

class ProductRepository implements ProductRepositoryInterface
{
    /**
     * Return a product with given ID
     * 
     * @param int $product+id
     * @return mixed
     */
    public function get($product_id)
    {
        return Product::with("brand", "category")->findOrFail($product_id);
    }

    /**
     * Return all products
     * 
     * @return mixed
     */
    public function all($withImages = false)
    {
        if ($withImages)
            return collect(
                $this->all()
                    ->reduce(function ($test, $product) {
                        $test[] = (object) array(
                            'Product' => $product,
                            'Images' => collect($product->images)->pluck("path")
                        );
                        return $test;
                    })
            );

        return Product::all();
    }

    /**
     * Return products with given condition
     * Only usable on basic operators for now
     * @param string $column
     * @param string $value
     * @param string $operator default "="
     */
    public function where($column, $value, $operator = "=")
    {
        return Product::with("brand", "category")->where($column, $operator, $operator == "LIKE" ? "%" . $value . "%" : $value)->get();
    }

    /**
     * validates product data from request
     * return mixed
     */
    public function validateRequest()
    {

        return tap(
            request()->validate([
                'category_id'    =>  'required',
                'brand_id'      =>  'sometimes',
                'product_name'  =>  'required | min: 10 | max: 100',
                'description'   =>  'required | min: 10',
                'price'         =>  ['required ', 'regex:/^\d+(\.\d{1,2})?$/', 'max:10', 'gt:0'],
                'qty'           =>  'required | digits_between: 1,3 | gt:0',
            ]),
            function () {
                if (request()->file('image')) {
                    request()->validate([
                        'image' =>  'file|image|max:5000'
                    ]);
                }
            }
        );
    }

    /**
     * Saves new product
     * 
     * @return mixed
     */
    public function add()
    {
        $product = Product::create($this->validateRequest());
        $this->saveImage($product->id);
        return $product;
    }

    /**
     * Deletes a product
     * 
     * @param int $product_id
     */
    public function delete($product_id)
    {
        $product = $this->get($product_id);
        Image::removeImageFolder("images/products/" . $product_id . "_" . Str::slug($product->product_name . "/"));
        $product->images()->delete();

        Product::destroy($product->id);
    }

    /**
     * Updates a product
     * 
     * @param int $product_id
     * @param array $product_data
     * @return mixed
     */
    public function update($product_id, $product_data)
    {
        $product = $this->get($product_id);
        $this->saveImage($product_id);
        return
            $product->update($product_data);
    }

    /**
     * Updates specified product's stock
     * 
     * @param int $product_id
     * @param int $qty
     * @return mixed
     */
    public function restock($product_id, $qty)
    {
        $product = $this->get($product_id);
        $new_qty = $product->qty + $qty;
        return
            $product->update(['qty' => $new_qty]);
    }


    /**
     * Return products with attached given tables
     * 
     * @param array $tables
     * @param int $perPage
     * @param array $order
     * @return mixed
     */
    public function with($tables, $perPage = 10, $order = ["created_at", "desc"])
    {
        if ($order)
            return Product::with($tables)->orderBy($order[0], $order[1])->paginate($perPage);
        else
            return Product::with($tables);
    }

    /**
     * Return list of products related to given product ID
     * 
     * @param int $product_id
     * @param int $limit
     * @return mixed
     */
    public function related_products($product_id, $limit)
    {
        $product = $this->get($product_id);
        $otherProducts = Product::with("brand", "category")->get()->filter(function ($otherProduct) use ($product) {
            return $otherProduct->id !== $product->id;
        });

        $related_products = array(
            'name' => $otherProducts
                ->where('product_name', 'LIKE', '%' . $product->product_name . '%')->take($limit),
            'brand' => $otherProducts->filter(function ($otherProduct) use ($product) {
                return $otherProduct->brand_id == $product->brand_id;
            })->take($limit),
            'category' => $otherProducts->filter(function ($otherProduct) use ($product) {
                return $otherProduct->category_id == $product->category_id;
            })->take($limit)
        );

        return array(
            'name'  => $related_products['name']->count() > 0 ? $related_products['name'] : null,
            'brand'  => $related_products['brand']->count() > 0 ? $related_products['brand'] : null,
            'category'  => $related_products['category']->count() > 0 ? $related_products['category'] : null,
        );
    }

    /**
     * Returns a collection from products table joined with brand and category tables
     * 
     * @param array $sort
     * @return mixed
     */
    public function joined($sort = array('created_at', 'desc'))
    {
        return Product::orderBy($sort[0], $sort[1])->with(['brand', 'category'])
            ->leftJoin('brands', 'products.brand_id', '=', 'brands.id')
            ->leftJoin('product_category', 'products.category_id', '=', 'product_category.id')
            ->select('products.*', 'brand_name', 'category_name');
    }

    /**
     * Search product with given parameters
     * @param collection request
     * @return mixed
     */

    public function search()
    {
        $per_page = request()->has('per_page') ? request()->input('per_page') : 20;

        $sort = request()->get('sort') ? request()->get('sort') : "created_at-desc";
        $sort = explode("-", $sort);

        $products = $this->joined($sort);

        if (request()->has('brands') && request()->input('brands') != "")
            $products->whereIn('brand_id', explode(",", request()->input('brands')));


        if (request()->has('categories') && request()->input('categories') != "")
            $products->whereIn('category_id', explode(",", request()->input('categories')));


        if (request()->has('search_term')) {
            $products->where('product_name', 'LIKE', '%' . request()->input('search_term') . '%');
        }
        return $products->paginate($per_page);
    }

    /**
     * Do a quick search for product
     * @return mixed
     */
    public function quick_search($product_name)
    {
        $result = $this->joined()->where('product_name', 'LIKE', '%' . $product_name . '%')->get();

        return array(
            'count' => $result->count(),
            'product'   => $result
        );
    }


    /**
     * Save uploaded image/s
     * @param int $id
     * @return bool
     */

    public function saveImage($id)
    {

        $product = $this->get($id);
        if ($image = request()->file('image')) {
            $product_folder = 'images/products/' . $product->id . '_' . Str::slug($product->product_name);
            if (!file_exists($product_folder))
                mkdir($product_folder);
            $image_save = Image::saveImage($image, $product_folder);
            $product
                ->images()
                ->create(["path" => $image_save, "original_name" => $image->getClientOriginalName()]);
        }

        return $product;
    }

    /**
     * Removes image of a product with given image path
     * @param int $id
     * @param string $image_path
     * @return mixed
     */
    public function removeImage($id, $image_path)
    {
        $product = $this->get($id);
        $product->images()->where('path', $image_path)->delete();

        return unlink($image_path) ? $this->get($id) : false;
    }
}
