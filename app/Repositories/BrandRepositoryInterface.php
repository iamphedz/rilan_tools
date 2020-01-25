<?php

namespace App\Repositories;

interface BrandRepositoryInterface
{
    /**
     * Get brand with given ID
     * 
     * @param int $id
     * @return mixed
     */
    public function get($id);

    /**
     * Validates brand variables
     * @return mixed
     */
    public function validateThis();

    /**
     * Get all brand
     *
     * @param string $type
     * @param int $perPage
     * @return mixed
     */
    public function all($type = null, $perPage = 10);

    /**
     * Add new brand
     * @return mixed
     */
    public function add();

    /**
     * Updates a brand
     * 
     * @param int $id
     * @return mixed
     */
    public function update($id);

    /**
     * Soft deletes a brand
     * @param int $id
     * return bool
     */
    public function softDelete($id);

    /**
     * Force deletes a brand
     * @param int $id
     * return bool
     */
    public function forceDelete($id);
}
