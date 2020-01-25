<?php

namespace App\Repositories;


interface PaymentRepositoryInterface
{

    /**
     * Validates payment details from request
     * @return mixed
     */
    public function validatePaymentDetails();

    /**
     * Get payment details with given payment ID
     * @param int $payment_id
     * @return mixed
     */
    public function get($payment_id);

    /**
     * Get payment details with given order request id
     * @param int $order_request_id
     * @return mixed
     */
    public function get_or_payment($order_request_id);

    /**
     * Store payment details
     * @return mixed
     */
    public function add();

    /** 
     * Update payment details
     * @return boolean
     */
    public function update();

    /**
     * Delete payment details
     * @param int $payment_id
     * @return boolean
     */
    public function delete($payment_id);

    /**
     * Save uploaded image/s
     * @param int $id
     * @return bool
     */

    public function saveImage($id);
}
