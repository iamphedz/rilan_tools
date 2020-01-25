<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Repositories\BrandRepositoryInterface;
use App\Repositories\ProductRepositoryInterface;

class BrandController extends Controller
{
    protected $brand, $product;

    /**
     * CategoryController constructor
     */

    public function __construct(BrandRepositoryInterface $brand, ProductRepositoryInterface $product)
    {
        $this->brand = $brand;
        $this->product = $product;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(
            array(
                'brands'    =>  $this->brand->all('paginated'),
                'brands_all'    =>  $this->brand->all()
            )
        );
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

        return json_encode($this->brand->add());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $brand = $this->brand->get($id);
        $products = $this->product->where('brand_id', $brand->id);

        return view('.products.brand', compact('brand', 'products'));
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
    public function update(Request $request)
    {
        return response()->json($this->brand->update($request->input('id')));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return response()->json($this->brand->forceDelete($id));
    }
}
