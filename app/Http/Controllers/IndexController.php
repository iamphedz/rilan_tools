<?php

namespace App\Http\Controllers;

use App\Http\CustomClass\Cart;
use Illuminate\Http\Request;
use App\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail;
use App\OrderRequest;
use App\OrderStatus;
use App\Repositories\ProductRepositoryInterface;
use Illuminate\Support\Facades\Session;

class IndexController extends Controller
{
    public function index()
    {
        $latest_products = Product::with("brand","category")->orderBy('created_at', 'desc')->paginate(5);
        $featured_products = Product::with("brand","category")->inRandomOrder()->get()->take(5);
        return view('index', compact('latest_products', 'featured_products'));
    }


    public function test(ProductRepositoryInterface $product)
    {
        dd(Product::find(83)->image_paths);
        return view('test');
    }

    public function send_contact_message(Request $request)
    {
        Mail::to("phedz.carreon@gmail.com")->send(new ContactFormMail($request->all()));

        return json_encode("Message has been send. Thank you!");
    }
}
