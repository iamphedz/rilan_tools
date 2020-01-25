<?php

namespace App\Http\Livewire;

use Livewire\Component;

class OrderNotifier extends Component
{

	public $order_notification;

	protected $listeners = ['echo:orders, order.created' => 'notifyNewOrder'];

	public function notifiyNewOrder($e) {
		$this->$order_notification = $e;
	}

    public function render()
    {
        return view('livewire.order_notifier');
    }
}
