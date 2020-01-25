<?php

namespace App\Repositories;


interface ProductRepositoryInterface
{
    /**
     * Get a product with given id
     * 
     * @param int $product_id
     */
    public function get($product_id);

    /**
     * Get all products
     * 
     * @return mixed
     */
    public function all();

    /**
     * validates product data from request
     * return mixed
     */
    public function validateRequest();

    /**
     * Saves new product
     * 
     * @return mixed
     */
    public function add();

    /**
     * Return products with given condition
     * Only usable on basic operators for now
     * @param string $column
     * @param string $value
     * @param string $operator default "="
     */
    public function where($column, $value, $operator = "=");

    /**
     * Deletes a product with given id
     * 
     * @param int $product_data
     */
    public function delete($product_id);

    /**
     * Updates a product
     * 
     * @param int $product_id
     * @param array $product_data
     */
    public function update($product_id, $product_data);

    /**
     * Updates specified product's stock
     * 
     * @param int $product_id
     * @param int $qty
     * @return mixed
     */
    public function restock($product_id, $qty);

    /**
     * Return products with attached given tables
     * 
     * @param array $tables
     * @param int $perPage
     * @param array $order
     * @return mixed
     */
    public function with($tables, $perPage = 10, $order = null);

    /**
     * Return list of products related to given product instance
     * 
     * @param int $product_id
     * @param int $limit
     * @return mixed
     */
    public function related_products($product_id, $limit);

    /**
     * Returns a collection from products table joined with brand and category tables
     * 
     * @param array $sort
     * @return mixed
     */
    public function joined($sort = array('created_at', 'desc'));

    /**
     * Search product with given parameters
     * @param collection request
     * @return mixed
     */

    public function search();

    /**
     * Save uploaded image/s
     * @param int $id
     * @return bool
     */

    public function saveImage($id);

    /**
     * Removes image of a product with given image path
     * @param int $id
     * @param string $image_path
     * @return mixed
     */
    public function removeImage($id,$image_path);

    /**
     * Do a quick search for product
     * @return mixed
     */
    public function quick_search($product_name);
}
