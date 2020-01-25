<?php

namespace App\Http\Controllers;

use App\UserSetting;
use App\User;
use Illuminate\Http\Request;

class UserSettingController extends Controller
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
        $user = User::findOrFail(request()->input('user_id'));
        if (!$user) return false;

        $settings = UserSetting::where('user_id',$user->id)->first();
        $user_settings = request()->input('setting');
        
        if ($user_settings) {
            $setting_id = $settings->id;
            if ($settings->update([
                'product' => $user_settings['product']
                ])
            )
                return UserSetting::findOrFail($setting_id);
        }
        else {
            return UserSetting::create(['user_id' => $user->id, 'product' => $user_settings['product']]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\UserSetting  $userSetting
     * @return \Illuminate\Http\Response
     */
    public function show(UserSetting $userSetting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\UserSetting  $userSetting
     * @return \Illuminate\Http\Response
     */
    public function edit(UserSetting $userSetting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\UserSetting  $userSetting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserSetting $userSetting)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\UserSetting  $userSetting
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserSetting $userSetting)
    {
        //
    }
}
