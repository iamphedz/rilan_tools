<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;

class NotificationsController extends Controller
{
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $notifications = User::findOrFail($id)->notifications()->take(5)->get()->map(function ($notification) {
            return $notification;
        });

        $unread_or = User::findOrFail($id)->unreadNotifications->filter(function ($notification) {
            return $notification->data['notif_type'] == "Order Request";
        })->count();

        $unread_product = User::findOrFail($id)->unreadNotifications->filter(function ($notification) {
            return $notification->data['notif_type'] == "Product";
        })->count();

        return json_encode(
            array(
                'notifications' => $notifications,
                'unread_count'  =>  array('or' => $unread_or, 'product' => $unread_product)
            )
        );
    }

    public function mark_all_read(Request $request)
    {
        User::findOrFail($request->get('id'))->unreadNotifications->markAsRead();

        return response()->json(true);
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
