<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\CustomClass\Cart;
use Illuminate\Support\Facades\Auth;
use App\Events\OrderRequestCreatedEvent;
use Illuminate\Support\Facades\Notification;
use App\Notifications\CancelOrderRequest;
use App\User;
use App\Events\ShoppingCartUpdatedEvent;
use App\Repositories\OrderRequestRepositoryInterface;


class OrderRequestController extends Controller
{

    protected $order_request;
    /**
     * OrderRequestController's constructor
     * 
     * @param mixed OrderRequestRepositoryInterface
     */
    public function __construct(OrderRequestRepositoryInterface $order_request)
    {
        $this->order_request = $order_request;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return response()->json($this->order_request->search());
    }

    /**
     * Get one order request with given id
     * @param int $id
     * @return mixed
     */
    public function get_one($id)
    {
        return response()->json($this->order_request->get($id));
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

        $order_request = $this->order_request->add();
        event(new OrderRequestCreatedEvent($order_request));

        return response()->json([
            'status'        =>  true,
            'message'       =>  "Order Request has been send.",
            'order_request' =>  $order_request
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return view('order_requests.track_order');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        switch ($request->input("update_type")) {
            case "status":
                return response()->json($this->order_request->update_status());
                break;
            case "order_data":
                return response()->json($this->order_request->update_data(request()->input("column")));
                break;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        Notification::send(
            User::all(),
            new CancelOrderRequest(
                $this->order_request->get($id)
            )
        );

        Auth::check() ? $this->order_request->forceDelete($id) : $this->order_request->softDelete($id);

        return response()->json("Order Request has been cancelled.");
    }


    /**
     * Search order request with given tracking_no
     * @return mixed
     */
    public function track_order(Request $request)
    {
        return response()->json($this->order_request->trackOrder($request->get('tracking_no')));
    }

    /**
     * Generates a printable/downloadable PDF file of order request
     * @param string $tracking_no
     * @return view
     */
    public function print($tracking_no)
    {
        return $this->order_request->print($tracking_no);
    }


    /**
     * Update
     */
}
