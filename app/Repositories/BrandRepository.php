<?php

namespace App\Repositories;

use App\Brand;
use Illuminate\Support\Str;
use App\Repositories\BrandRepositoryInterface;

class BrandRepository implements BrandRepositoryInterface
{
    /**
     * Get brand with given ID
     * 
     * @param int $id
     * @return mixed
     */
    public function get($id)
    {
        return Brand::findOrFail($id);
    }

    /**
     * Validates brand variables
     * @return mixed
     */
    public function validateThis()
    {
        return request()->validate([
            'brand_name'  =>  'required | max: 50'
        ]);
    }

    /**
     * Get all brand
     *
     * @param string $type
     * @param int $perPage
     * @return mixed
     */
    public function all($type = null, $perPage = 10)
    {
        $brand = array(
            'paginated' => Brand::orderBy('brand_name', 'asc')->paginate($perPage),
            'indexed'  => Brand::all()->reduce(function ($reduce, $brand) {
                $reduce[$brand->id] = $brand;
                return $reduce;
            }),
            'all' => Brand::orderBy('brand_name','asc')->get()
        );

        return $type ? $brand[$type] : $brand['all'];
    }

    /**
     * Add new brand
     * @return mixed
     */
    public function add()
    {
        return Brand::create($this->validateThis());
    }

    /**
     * Updates an brand
     * 
     * @param int $id
     * @param array $brand_data
     * @return mixed
     */
    public function update($id)
    {
        return $this->get($id)->update($this->validateThis());
    }

    /**
     * Soft deletes an brand
     * @param int $id
     * return bool
     */
    public function softDelete($id)
    {
        return $this->get($id)->delete();
    }

    /**
     * Force deletes an brand
     * @param int $id
     * return bool
     */
    public function forceDelete($id)
    {
        return $this->get($id)->forceDelete();
    }
}
