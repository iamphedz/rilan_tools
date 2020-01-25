<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ProductRepositoryInterface;
use App\Repositories\CategoryRepositoryInterface;
use Illuminate\Support\Str;
use App\Http\CustomClass\Image;
use App\Repositories\BrandRepositoryInterface;
use Carbon\Carbon;

class ProductController extends Controller
{
    protected $product, $brands, $categories;

    /**
     * ProductController's constructor
     * 
     * @param ProductRepositoryInterface $product
     */
    public function __construct(ProductRepositoryInterface $product, CategoryRepositoryInterface $category, BrandRepositoryInterface $brand)
    {
        $this->product = $product;

        $this->brands = $brand;

        $this->categories = $category;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$products = $this->product->with(["brand", "category"])->orderBy('created_at', 'desc')

        return response()
            ->json(
                [
                    'products'  =>  $this->product->with(["brand", "category"], 10),
                    'products_all'  =>  $this->product->all(),
                    'brands'    =>  $this->brands->all('indexed'),
                    'categories'    =>  $this->categories->all('indexed')
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
        return response()->json($this->product->add());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = $this->product->get($id);
        return view('products.view', compact('product'));
    }


    public function show_all()
    {
        return view('products.product_list');
    }

    /**
     * Search product with parameters given in request
     * 
     * @return mixed
     */
    public function search()
    {
        return response()->json(
            [
                'product_all'   =>  $this->product->all(),
                'products'  =>  $this->product->search(),
                'brands'    =>  $this->brands->all(),
                'categories'    =>  $this->categories->all()
            ]
        );
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
        $product = $this->product->update(
            $request->input('id'),
            $this->product->validateRequest()
        );

        return response()->json($product);
    }

    /**
     * restock specified product.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restock(Request $request)
    {
        $product = $this->product->restock(
            $request->input('id'),
            $request->input('qty')
        );

        return response()->json($product);
    }

    /**
     * uploads and save image to database for specified product
     *
     */
    public function upload_image(Request $request)
    {
        return response()->json($this->product->saveImage($request->input('id')));
    }

    /**
     *
     *
     */
    public function remove_image(Request $request)
    {
        return response()->json($this->product->removeImage($request->input('id'), $request->input('image_path')));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->product->delete($id);

        return response()->json("Record Deleted.");
    }

    /**
     * Return list of related products
     * 
     * @param int $id
     * @return mixed
     */

    public function related_products($id)
    {
        if ($this->product->get($id)) {
            return response()->json(
                [
                    'related_products'  =>  $this->product->related_products($id, 10),
                    'brands'            =>  $this->brands->all('indexed'),
                    'categories'        =>  $this->categories->all('indexed')
                ]
            );
        }
    }

    /**
     * handles the quick search request
     * if search matches only one product, it will redirect to that product's view page
     * if it matches several result, it will redirect to the product list page
     * @return view
     */
    public function quick_search(Request $request)
    {
        $result = $this->product->quick_search(request()->input("search_term"));

        if ($result['count'] > 1) {
            return response()->json(array(
                'count' => $result['count'],
                'link' => '/products?quick_search=' . request()->input('search_term')
            ));
        } else if ($result['count'] == 1) {
            return response()->json(array(
                'count' => $result['count'],
                'link' => "/products/view/" . $result['product']->first()->id . "-" . Str::slug($result['product']->first()->product_name)
            ));
        }

        return response()->json(false);
    }
}
