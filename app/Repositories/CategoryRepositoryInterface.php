<?php

namespace App\Repositories;

interface CategoryRepositoryInterface
{
    /**
     * Get category with given ID
     * 
     * @param int $id
     * @return mixed
     */
    public function get($id);

    /**
     * Validates category variables
     * @return mixed
     */
    public function validateThis();

    /**
     * Get all category
     *
     * @param string $type
     * @param int $perPage
     * @return mixed
     */
    public function all($type, $perPage = 10);

    /**
     * Add new category
     * @return mixed
     */
    public function add();

    /**
     * Updates a category
     * 
     * @param int $id
     * @return mixed
     */
    public function update($id);

    /**
     * Soft deletes a category
     * @param int $id
     * return bool
     */
    public function softDelete($id);

    /**
     * Force deletes a category
     * @param int $id
     * return bool
     */
    public function forceDelete($id);
}
