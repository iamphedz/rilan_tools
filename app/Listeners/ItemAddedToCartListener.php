<?php

namespace App\Listeners;

use App\Providers\ShoppingCartUpdatedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ItemAddedToCartListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ShoppingCartUpdatedEvent  $event
     * @return void
     */
    public function handle(ShoppingCartUpdatedEvent $event)
    {
        //
    }
}
