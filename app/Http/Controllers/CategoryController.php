<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Brand;
use App\Repositories\BrandRepositoryInterface;
use App\Repositories\CategoryRepositoryInterface;
use App\Repositories\ProductRepositoryInterface;

class CategoryController extends Controller
{
    protected $brand, $category, $product;

    /**
     * CategoryController constructor
     */

    public function __construct(BrandRepositoryInterface $brand, CategoryRepositoryInterface $category, ProductRepositoryInterface $product)
    {
        $this->brand = $brand;
        $this->category = $category;
        $this->product = $product;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $brands = array();
        foreach (Brand::all() as $brand) {
            $brands[$brand->id] = $brand;
        }
        return response()->json(
            [
                'indexed'    =>  $this->category->all('indexed'),
                'categories'    =>  $this->category->all('paginated'),
                'brands'      =>  $this->brand->all('indexed'),
                'products'      =>  $this->product->all(),
            ]
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
        return response()->json($this->category->add());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = $this->category->get($id);
        $products = $this->product->where('category_id', $category->id);

        return view('.products.category', compact('category', 'products'));
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
        return response()->json($this->category->update($request->input('id')));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return response()->json($this->category->forceDelete($id));
    }
}
