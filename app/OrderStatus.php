<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    protected $fillable = ["status_order", "status", "remark", "reminders"];
    protected $status_order_by_payment = [
    	1	=>	[
    		"name" => "Cash on Delivery",
	    	"code" => "cod",
	    	"order" => [1,2,3,4]
	    ],
	    2 => [
    		"name" => "Pay on Pickup",
	    	"code" => "pickup",
	    	"order" => [1,2,4]

	    ],
	    3	=>	[
	    	"name"	=>	"Remittance Center",
	    	"code"	=>	"remittance",
	    	"order"	=>	[2,3,4,5,6]
	    ],
	    4	=>	[
	    	"name"	=>	"Bank",
	    	"code"	=>	"bank",
	    	"order"	=>	[2,3,4,5,6]
	    ],
	    5	=>	[
	    	"name"	=>	"eLoad",
	    	"code"	=>	"eload",
	    	"order"	=>	[2,3,4,5,6]
	    ]
    ];

    public function getStatusOrder($mode) {
    	return $this->status_order_by_payment[$mode];
    }

    public function order_requests()
    {
        return $this->hasMany("App\OrderRequest", "status", "id");
    }

}
