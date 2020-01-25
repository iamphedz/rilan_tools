<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use DebugBar\DebugBar;

class AdminPanelController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin_panel');
    }
}
