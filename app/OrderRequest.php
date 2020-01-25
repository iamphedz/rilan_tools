<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class OrderRequest extends Model
{
    use SoftDeletes;

    protected $table = "order_requests";
    protected $fillable = ["tracking_no", "name", "contact_no", "email", "address", "order_data", "status","mode_of_payment"];
    protected $casts = [
        "status"    =>  'integer',
        "order_data"    =>  'array'
    ];
    protected $appends = ["status_details", "has_expired", "is_new","status_order"];
    protected $expiration = 48;
    
    public function getExpiration($format = 'date')
    {
        if ($format == 'date')
            return Carbon::now()->subHours($this->expiration)->toDateString();
        else
            return Carbon::now()->subHours($this->expiration)->timestamp;
    }

    /**
     * Set updated_at column as Carbon difftohuman format
     */
    public function getUpdatedAtAttribute()
    {
        return Carbon::parse($this->attributes['updated_at'])->diffForHumans();
    }

    public function getStatusOrderAttribute()
    {
        return OrderStatus::find($this->status)->getStatusOrder($this->mode_of_payment);
    }

    /**
     * Custom attribute that indicates if order request already expired
     * @return boolean
     */
    public function getHasExpiredAttribute()
    {
        return strtotime($this->created_at) < $this->getExpiration('timestamp') && $this->status < 3 ? true : false;
    }

    /**
     * Custom attribute that indicates if order request is still new
     * @return boolean
     */
    public function getIsNewAttribute()
    {
        return strtotime($this->created_at) >= $this->getExpiration('timestamp') ? true : false;
    }

    public function scopeFilter($query, $condition)
    {
        if ($condition !== "all")
            if ($condition !== "expired")
                //return $query->where('status', $condition)->whereDate('created_at','>=', $this->getExpiration());
                return $query->where('status', $condition);
            else {
                return $query->where('status', '<', 3)->whereDate('created_at','<=', $this->getExpiration());
            }
        else
            return;
    }

    /**
     * Custom attribute that returns the order status model related to the status column
     * @return mixed
     */
    public function getStatusDetailsAttribute()
    {
        return OrderStatus::find($this->status);
    }

    public function status()
    {
        return $this->hasOne('App\OrderStatus', 'id', 'status');
    }

    public function payment()
    {
        return $this->hasOne('App\Payment', 'order_request_id', 'id');
    }
}
