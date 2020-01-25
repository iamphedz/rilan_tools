<?php

namespace App\Repositories;

use App\Http\CustomClass\Image;
use App\Payment;
use Carbon\Carbon;
use Illuminate\Support\Str;

class PaymentRepository implements PaymentRepositoryInterface
{
    /**
     * Validates payment details from request
     * @return mixed
     */
    public function validatePaymentDetails()
    {
        return tap(
            request()->validate([
                "order_request_id"  =>  'required',
                "payment_details"   =>  'required',
            ]),
            function () {
                if (request()->file('images')) {
                    request()->validate([
                        'images' =>  'file|image|max:5000'
                    ]);
                }
            }
        );
    }

    /**
     * Get payment details with given payment id
     * @param int $payment_id
     * @return mixed
     */
    public function get($payment_id)
    {
        return Payment::findOrFail($payment_id)->with('order_request')->first();
    }

    /**
     * Get payment details with given order request id
     * @param int $order_request_id
     * @return mixed
     */
    public function get_or_payment($order_request_id)
    {
        $payment = Payment::where('order_request_id', $order_request_id)->with('order_request');
        return $payment->count() > 0 ? $payment->first() : false;
    }


    /**
     * Store payment details
     * @return mixed
     */
    public function add()
    {
        $payment = Payment::create($this->validatePaymentDetails());

        $this->saveImage($payment->id);

        return $payment;
    }

    /** 
     * Update payment details
     * @return boolean
     */
    public function update()
    {
        $payment = $this->get(request()->input('id'));

        $this->saveImage($payment->id);

        $payment->update($this->validatePaymentDetails());
        return $payment;
    }

    /**
     * Delete payment details
     * @param int $payment_id
     * @return boolean
     */
    public function delete($payment_id)
    {
        $payment = $this->get($payment_id);
        Image::removeImageFolder("images/payments/" . Str::slug("Tracking No_" . $payment->order_request->tracking_no));
        $this->get($payment_id)->delete();
    }

    /**
     * Save uploaded image/s
     * @param int $id
     * @return bool
     */

    public function saveImage($id)
    {
        if ($image = request()->file('images')) {

            $payment = $this->get($id);
            $tracking_no = $payment->order_request->tracking_no;
            $image_save = Image::saveImage($image, "images/payments", Str::slug("Tracking No_" . $tracking_no));

            $payment
                ->image()
                ->create(["path" => $image_save, "original_name" => $image->getClientOriginalName()]);

            dd('test');
        }
        return false;
    }
}
