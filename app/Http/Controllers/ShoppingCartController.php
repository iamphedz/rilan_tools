<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\CustomClass\Cart;
use App\Product;
use App\Brand;
use App\Events\ShoppingCartUpdatedEvent;

class ShoppingCartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'cart_products' =>  Cart::getAll(),
            'cart_item_count' =>  Cart::getAll() ? collect(Cart::getAll())->count() : 0,
            'cart_data'     =>  Cart::encodeData()
        ]);
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

        Cart::updateItem([
            'product_id'    =>  $request->input('product_id'),
            'qty'           =>  $request->input('qty') ? $request->input('qty') : 1
        ]);

        return array(
            'status'    =>  true,
            'msg'      =>  "Cart item list has been updated."
        );
    }

    /**
     * compeletely empty out the session cart
     */
    public function empty_cart()
    {
        event(new ShoppingCartUpdatedEvent(Cart::encodeData()));
        return response()->json(Cart::empty());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //Cart::empty();
        //dd(Cart::getAll());
        return view('shopping_cart.show');
    }

    public function checkout($session_id)
    {
        return view('shopping_cart.checkout', compact('session_id'));
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
        $product = Product::find($id);

        if ($product) {
            event(new ShoppingCartUpdatedEvent(Cart::encodeData()));
            return response()->json(Cart::removeItem($product->id));
        }
    }

    public function item_exist()
    {
        $product = Cart::getOne(request()->input('product_id'));
        return response()->json($product ? true : false);
    }
}
