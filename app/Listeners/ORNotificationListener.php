<?php

namespace App\Listeners;

use App\Events\OrderRequestCreatedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderRequestMail;
use App\Mail\OrderRequestAdminMail;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewOrderRequest;
use App\User;

class ORNotificationListener implements ShouldQueue
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
     * @param  OrderRequestCreatedEvent  $event
     * @return void
     */
    public function handle(OrderRequestCreatedEvent $event)
    {
        //Mail::to(["phedz.carreon@gmail.com", "rilantoolssupply@gmail.com"])->send(new OrderRequestAdminMail($event->order_request));
        Mail::to(["phedz.carreon@gmail.com"])->send(new OrderRequestAdminMail($event->order_request));
        //Mail::to($event->order_request->email)->send(new OrderRequestMail($event->order_request));

        $users = User::all();
        Notification::send($users, new NewOrderRequest($event->order_request));
    }
}
