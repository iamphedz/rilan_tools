@extends('layouts.app')
@section('content')
@inject ('Str', 'Psy\Util\Str')
<div class="flex flex-col mx-auto md:p-2">
    <h1 class="block w-full text-center text-2xl md:text-4xl md:font-thin">Latest Products</h1>
    <div class="flex flex-wrap flex-col md:flex-row mt-10 w-full">
        @foreach($latest_products as $product)
            <product-thumbnail :product="{{ json_encode($product) }}"></product-thumbnail>
        @endforeach
    </div>
</div>

<div class="flex flex-col md:flex-row mx-auto md:p-2">
    <div class="w-full md:p-5 text-justify text-gray-700 mx-auto leading-relaxed">
        <h1 class="block w-full text-center text-2xl md:text-4xl md:font-thin my-5 md:my-10">Online Shopping</h1>
        <p class="text-center">
            Order products through our "Shopping Cart" service. Add items on it and checkout to place an order request. You can access the Shopping Cart by clicking the icon on your bottom right screen. Or just click the button below:
        </p>
        <a href="{{url('/shopping_cart')}}" class="bg-green-800 text-gray-200 font-semibold py-2 px-4 rounded block w-64 text-center mx-auto mt-5 hover:bg-green-700"><i class="fa fa-shopping-cart" aria-hidden="true"></i> SHOPPING CART</a>
    </div>

</div>
<div class="flex flex-col md:flex-row mx-auto md:p-2">
    <div class="w-full md:p-5 text-gray-700 mx-auto leading-relaxed text-center flex-col">
        <h1 class="block w-full text-center text-2xl md:text-4xl md:font-thin my-5 md:my-10">Want to check the status of your order request?</h1>
        Check your order status by searching its Tracking # here:
        <form method="get" action="{{ url('/track_order') }}" class="flex flex-row mt-5 w-full">
            <div class="w-9/12">
                <input class="appearance-none border rounded-l text-gray-700 leading-tight p-2 w-full focus:outline-none focus:border-green-500 bg-gray-200 focus:bg-white" type="text" name="tracking_no" required />
            </div>
            <button class="bg-green-800 text-gray-200 font-semibold lg:text-base text-xs px-4 rounded-r w-32 md:w-3/12 hover:bg-green-700">TRACK ORDER</button>
        </form>

    </div>
</div>
<div class="flex flex-col mx-auto md:p-2 mt-10">
    <h1 class="block w-full text-center text-2xl md:text-4xl md:font-thin">Other Products</h1>
    <div class="flex flex-wrap flex-col md:flex-row mt-10 w-full">
        @foreach($featured_products as $featured_product)
        <product-thumbnail :product="{{ json_encode($featured_product) }}"></product-thumbnail>
        @endforeach
    </div>
</div>
<div class="flex flex-col md:flex-row md:p-2 mt-10" id="about">
    <div class="w-full md:w-8/12 flex-none md:p-5 text-justify text-gray-700 mx-auto leading-relaxed">
        <h1 class="block w-full text-center text-2xl md:text-4xl md:font-thin my-5 md:mb-10">About</h1>
        Rilan Tool Supply is an industrial tool shop that started in 2016 and owned by Mr. John Mark Tudilla. From local to social media advertising, Rilan Tool Supply is now also open to online purchasing of their products.
    </div>
    <div class="w-full md:w-4/12 h-auto flex-none md:p-5 mt-2 mx-auto">
        <img src="{{ asset('images/45085785_182691055952439_4775128486320799744_o.jpg') }}" alt="" class="w-full mx-auto">
    </div>
</div>
<div class="flex flex-wrap md:flex-no-wrap mx-auto md:p-2 items-start">
    <div class="w-full md:w-1/2 flex-none md:p-5 text-justify text-gray-700 mx-auto leading-relaxed">
        <h1 class="block w-full text-center text-2xl md:text-4xl md:font-thin my-5 md:my-10">Testimonials</h1>
        We deliver cheap and quality products nationwide. Here are some of our customer's testimonials.
    </div>
    <div class="w-full md:w-1/2 flex-none md:p-5 text-justify text-gray-700 mx-auto leading-relaxed" id="contact">
        <h1 class="block w-full text-center text-2xl md:text-4xl md:font-thin my-5 md:my-10">Contact Us</h1>
        In case you have questions or inquiries, you can send us a message below. You can also use this form to send payment information if you have used our order request service.
        <contact-form></contact-form>
    </div>
</div>
@endsection