<?php

namespace App\Repositories;

use App\Http\CustomClass\Cart;
use App\OrderRequest;
use App\Repositories\ProductRepositoryInterface;
use Carbon\Carbon;
use Auth;
use Illuminate\Support\Str;
use PDF;

class OrderRequestRepository implements OrderRequestRepositoryInterface
{
    protected $product, $payment;
    public function __construct(ProductRepositoryInterface $product)
    {
        $this->product = $product;
    }
    /**
     * Get order request with given ID
     * 
     * @param int $id
     * @return mixed
     */
    public function get($id)
    {
        return OrderRequest::withTrashed()->with("payment")->where("id", $id)->first();
    }

    /**
     * Get all order request
     * 
     * @return mixed
     */
    public function all()
    {
        return OrderRequest::withTrashed()->with("payment")->orderBy('created_at', 'desc');
    }

    /**
     * Returns list of order request with given filter
     * @param mixed request()
     * @return mixed
     */
    public function search()
    {
        if (request()->has("search_term") != "" || request()->has("filter")) {
            return $this
                ->all()
                ->where('tracking_no', 'LIKE', '%' . request()->input('search_term') . '%')
                ->orWhere('name', 'LIKE', '%' . request()->input('search_term') . '%')
                ->orWhere('address', 'LIKE', '%' . request()->input('search_term') . '%')
                ->orWhere('email', 'LIKE', '%' . request()->input('search_term') . '%')
                ->orWhere('contact_no', 'LIKE', '%' . request()->input('search_term') . '%')
                ->filter(request()->input("filter"))
                ->paginate(10);
        } else {
            return $this->all()
                ->paginate(10);
        }
    }

    /**
     * Validates order request variables from request
     * @return mixed
     */
    public function validateOrder()
    {
        return
            request()->validate([
                'name'          =>  'required | min:10 | max:50',
                'address'       =>  'required | min:20 | max:70',
                'email'         =>  'required | string | email | max:255',
                'contact_no'    =>  'required | regex:/^([0-9\s\-\+\(\)]*)$/ | min:10',
                'order_data'    =>  'required',
                'mode_of_payment' => 'required'
            ]);
    }

    /**
     * finalize the computation of order data before saving
     * @return mixed
     */
    public function finalizeOrder()
    {
        $order_request = $this->validateOrder();
        $order_items = collect($order_request['order_data']['items']);

        $order_request['order_data']['sub_total'] = $order_items->reduce(function($total, $item) {
            return $total + ($item['price']*$item['qty']);
        },0);

        return $order_request;
    }

    /**
     * Add new order request
     * @return mixed
     */
    public function add()
    {
        $order_request = OrderRequest::create($this->finalizeOrder());
        return $this->update($order_request->id, [
            'tracking_no'   =>  $this->generateTrackingNo($order_request->id)
        ]);
    }

    /**
     * Updates an order request
     * 
     * @param int $id
     * @param array $order_data
     * @return mixed
     */
    public function update($id, $order_data)
    {
        $update = $this->get($id)->update($order_data);

        if ($update)
            return $this->get($id);
        else
            return false;
    }

    /**
     * Soft deletes an order request
     * @param int $id
     * return bool
     */
    public function softDelete($id)
    {
        $this->update($id, ["status" => 5]);
        return OrderRequest::findorFail($id)->delete();
    }

    /**
     * Force deletes an order request
     * @param int $id
     * return bool
     */
    public function forceDelete($id)
    {
        return OrderRequest::findOrFail($id)->forceDelete();
    }

    /**
     * Generates a unique tracking no
     * @param int $id
     * return string
     */
    public function generateTrackingNo($id)
    {
        $charset = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $id_length = strlen($id);
        $tracking_pad = "";
        for ($i = 0; $i <= (9 - $id_length); $i++) {
            $tracking_pad = $tracking_pad . rand(0, 9);
        }
        return substr($charset, rand(0, 25), 1) . $id_length . "-" . $tracking_pad . $id;
    }

    /**
     * Custom tracking no descryptor
     * @param string $trackingNo
     * return int
     */
    public function decodeTrackingNo($trackingNo)
    {
        $split = explode("-", $trackingNo);
        $id_length = preg_replace("/^[A-Z]/", "", $split[0]);
        $id = substr($split[1], (-1 * $id_length));
        return $id;
    }


    /**
     * Find an order request with given $trackingNo
     * @param string $trackingNo
     * @return mixed
     */
    public function trackOrder($trackingNo)
    {
        $order_request = $this->all()->where('tracking_no', $trackingNo)
            ->get()
            ->first();

        if ($order_request) {
            $items = collect($order_request->order_data['items']);

            $product_instance = $this->product;

            $product_instances = $items->reduce(function ($product_instances, $item) use ($product_instance) {
                $product_instances[$item['id']]['instance'] = $product_instance->get($item['id']);
                $product_instances[$item['id']]['order_data'] = $item;
                return $product_instances;
            });
        }

        return $order_request ? ["order_request" => $order_request, "product_instances" => $product_instances] : false;
    }

    /**
     * Returns printable/downloadable PDF view of order request with given tracking no
     * @param string $trackingNo
     * @return mixed
     */
    public function print($trackingNo)
    {

        if ($order_request = $this->trackOrder($trackingNo)) {
            //return view('orders.pdf', compact('order_request', 'order_data'));
            //dd($order_request);
            return PDF::loadView(
                'order_requests.pdf',
                [
                    'order_request' => $order_request["order_request"],
                    'order_data' => $order_request["order_request"]->order_data,
                    'product_instances' => $order_request['product_instances']
                ]
            )
                ->setPaper('a4', 'portrait')
                ->stream("OR-"
                    . $order_request["order_request"]->tracking_no
                    . "-" . Str::slug($order_request["order_request"]->name)
                    . '.pdf');
        }
        return view('order_tracking');
    }

    /**
     * Updates the status of order request with given id
     * @return mixed
     */
    public function update_status()
    {
        $order_request = $this->get(request()->input("id"));

        if ($order_request->status != request()->input("status")) {
            $status_before = $order_request->status;
            $update_result = $this->update(request()->input("id"), ["status" => request()->input("status")]);
            if ($status_before === 5)
                $update_result->deleted_at = null;
                $update_result->save();
                $update_result->restore();

            return $update_result;

        }
    }

    /**
     * Updates the order request data
     * @param string $column
     * @return mixed
     */
    public function update_data($column)
    {
        $order_request = $this->get(request()->input("id"));
        $value = request()->input("value");
        $order_data = $order_request->order_data;
        switch ($column) {
            case 'shipping_fee':
                $order_data['fees']['shipping'] = $value;
                /**if ($value > 0 && $order_request->status == 2) {
                    $this->update(request()->input("id"), ["status" => 3]);
                }*/
                break;
            case 'discount':
                if (request()->input('discount_type') === 'flat')
                    $order_data['fees']['discount'] = $value;
                else
                    $order_data['fees']['discount'] = $order_data['sub_total']*($value/100);
                break;
        }
        $update_status = $order_request->update(['order_data' => $order_data]);

        return array(
            'status' => $update_status,
            'message' => 'Order has been updated.',
            'order_request' => $this->get(request()->input("id"))
        );
    }

    /**
     * Get payment details with given order request ID
     * @param PaymentRepositoryInterface $payment
     * @param int $order_request_id
     * @return mixed
     */
    public function get_payment(PaymentRepositoryInterface $payment, $order_request_id)
    {
        //
    }
}
