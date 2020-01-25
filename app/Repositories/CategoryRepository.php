<?php

namespace App\Repositories;

use App\Product_Category;
use Illuminate\Support\Str;
use App\Repositories\CategoryRepositoryInterface;

class CategoryRepository implements CategoryRepositoryInterface
{
    /**
     * Get category with given ID
     * 
     * @param int $id
     * @return mixed
     */
    public function get($id)
    {
        return Product_Category::findOrFail($id);
    }

    /**
     * Validates category variables
     * @return mixed
     */
    public function validateThis()
    {
        return request()->validate([
            'category_name'  =>  'required | max: 50'
        ]);
    }

    /**
     * Get all category
     *
     * @param string $type
     * @param int $perPage
     * @return mixed
     */
    public function all($type = null, $perPage = 10)
    {
        $category = array(
            'paginated' => Product_Category::orderBy('category_name', 'asc')->paginate($perPage),
            'indexed'  => Product_Category::all()->reduce(function ($reduce, $category) {
                $reduce[$category->id] = $category;
                return $reduce;
            }),
            'all' => Product_Category::orderBy('category_name','asc')->get()
        );

        return $type ? $category[$type] : $category['all'];
    }

    /**
     * Add new category
     * @return mixed
     */
    public function add()
    {
        return Product_Category::create($this->validateThis());
    }

    /**
     * Updates an category
     * 
     * @param int $id
     * @param array $category_data
     * @return mixed
     */
    public function update($id)
    {
        return $this->get($id)->update($this->validateThis());
    }

    /**
     * Soft deletes an category
     * @param int $id
     * return bool
     */
    public function softDelete($id)
    {
        return $this->get($id)->delete();
    }

    /**
     * Force deletes an category
     * @param int $id
     * return bool
     */
    public function forceDelete($id)
    {
        return $this->get($id)->forceDelete();
    }
}
