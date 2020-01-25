@extends('layouts/app')
@section('title')
{{ 'Brand: '.$brand->brand_name }} -
@endsection
@section('content')
<div class="container flex flex-col md:flex-no-wrap w-full">
    <div class="block flex-wrap flex items-center text-left my-1 text-xs text-gray-600 py-2">
        <a href="{{ url('/') }}" class="inline p-2 font-normal">Home</a>
        <span class="inline font-extrabold text-gray-400"><i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
        <a href="{{ url('/products/all-brands') }}" class="inline p-2 font-normal">Brands</a>
        <span class="inline font-extrabold text-gray-400"><i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
        <span class="inline p-2 font-normal text-gray-400">{{ $brand->brand_name }}</span>
    </div>
    <div class="flex flex-wrap md:flex-col px-2 py-10">
        <span class="block w-full text-center text-2xl md:text-4xl md:font-thin my-10">{{ $brand->brand_name }}</span>
        <div class="py-5 w-full">
            <span class="font-light mb-3 block md:text-left text-center text-xl">{{ count($products) }} product(s) available.</span>
        </div>
    </div>
    <div class="flex md:flex-row flex-wrap w-full mx-auto p-2">
        @foreach($products as $product)
        <product-thumbnail :product="{{ json_encode($product) }}"></product-thumbnail>
        @endforeach
    </div>

</div>

@endsection