<?php

namespace App\Http\Controllers;

use App\Repositories\PaymentRepositoryInterface;
use App\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected $payment;

    /**
     * PaymentController __construct
     */
    public function __construct(PaymentRepositoryInterface $payment)
    {
        $this->payment = $payment;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $payment = $this->payment->add();
        return response()->json($payment);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        return response()->json($this->payment->update());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payment $payment)
    {
        //
    }

    /**
     * Returns payment details with given order request id
     * 
     * @param int $order_request_id
     * @return mixed
     */
    public function get_or_payment($order_request_id)
    {
        return response()->json($this->payment->get_or_payment($order_request_id));
    }
}
