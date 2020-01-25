@extends('layouts/app')
@section('title')
{{ ($product->brand_id == 13 ? "" : $product->brand->brand_name)." ".$product->product_name." -" }}
@endsection
@section('content')
<div class="container flex flex-col lg:flex-no-wrap w-full">
    <div class="block flex-wrap flex items-center text-left my-1 text-xs text-gray-600 py-2">
        <a href="{{ url('/') }}" class="inline p-2 font-normal">Home</a>
        <span class="inline font-extrabold text-gray-400"><i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
        <a href="{{ $product->path('category') }}" class="inline p-2 font-normal">{{ $product->category->category_name }}</a>
        <span class="inline font-extrabold text-gray-400"><i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
        <a href="{{ $product->path('brand') }}" class="inline p-2 font-normal">{{ $product->brand->brand_name }}</a>
        <span class="inline font-extrabold text-gray-400"><i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
        <span class="inline p-2 font-normal text-gray-400">{{ $product->product_name }}</span>
    </div>
    <div class="flex md:flex-row flex-col mx-auto px-2 py-10 view-product">
        <div class="md:w-1/2 w-full">
            <img src="{{ url($product->image_paths[0]) }}" alt="" class="img-thumbnail h-auto">
            @if ($product->image_paths)
            <div class="gallery block flex flex-row py-2 h-40 mt-10 overflow-x-scroll">
                @foreach ($product->image_paths as $image)
                <img src="{{ url($image) }}" alt="" class="inline-block border-2 mb-2 cursor-pointer hover:border-green-400 w-32">
                @endforeach
            </div>
            @endif
        </div>
        <div class="flex flex-col lg:px-10 md:w-1/2 w-full pl-5">
            <span class="block text-center lg:text-left text-xl lg:text-3xl lg:font-light font-semibold">{{ $product->product_name }}</span>
            <span class="block pt-2 text-gray-600 font-thin text-xs lg:text-xl text-center lg:text-left">{{ $product->brand_id == 13 ? "" : $product->brand->brand_name }}</span>
            <div class="flex items-center md:flex-row flex-col md:justify-between w-full mt-5">
                <div class="text-xl lg:text-4xl font-semibold">&#8369; {{ number_format($product->price,2) }}</div>
                <div class="float-right text-xs lg:text-base md:m-0 mt-2">In Stock: {{ $product->qty }}</div>
            </div>
            <p class="mt-5 mb-10 leading-tight text-sm">{{ $product->description }}</p>
            <add-cart-qty :product="{{ $product }}"></add-cart-qty>
        </div>
    </div>
    <related-products :product_id="{{ $product->id }}" :product="{{ $product }}"></related-products>
</div>

@endsection