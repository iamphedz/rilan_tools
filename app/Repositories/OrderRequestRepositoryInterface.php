<?php

namespace App\Repositories;


interface OrderRequestRepositoryInterface
{
    /**
     * Get order request with given ID
     * 
     * @param int $id
     * @return mixed
     */
    public function get($id);

    /**
     * Validates order request variables
     * @return mixed
     */
    public function validateOrder();

    /**
     * finalize the computation of order data before saving
     * @return mixed
     */
    public function finalizeOrder();
    
    /**
     * Get all order request
     * 
     * @return mixed
     */
    public function all();

    /**
     * Add new order request
     * @return mixed
     */
    public function add();

    /**
     * Returns list of order request with given filter
     * @param mixed request()
     * @return mixed
     */
    public function search();

    /**
     * Updates an order request
     * 
     * @param int $id
     * @param array $order_data
     * @return mixed
     */
    public function update($id, $order_data);

    /**
     * Soft deletes an order request
     * @param int $id
     * return bool
     */
    public function softDelete($id);

    /**
     * Force deletes an order request
     * @param int $id
     * return bool
     */
    public function forceDelete($id);

    /**
     * Generates a unique tracking no
     * @param int $id
     * return string
     */
    public function generateTrackingNo($id);

    /**
     * Custom tracking no descryptor
     * @param string $trackingNo
     * return int
     */
    public function decodeTrackingNo($trackingNo);

    /**
     * Find an order request with given $trackingNo
     * @param string $trackingNo
     * @return mixed
     */
    public function trackOrder($trackingNo);

    /**
     * Returns printable/downloadable PDF view of order request with given tracking no
     * @param string $trackingNo
     * @return mixed
     */
    public function print($trackingNo);

    /**
     * Updates the status of order request with given id
     * @return mixed
     */
    public function update_status();

    /**
     * Get payment details with given order request ID
     * @param PaymentRepositoryInterface $payment
     * @param int $order_request_id
     * @return mixed
     */
    public function get_payment(PaymentRepositoryInterface $payment, $order_request_id);
}
