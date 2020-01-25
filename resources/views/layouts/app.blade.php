<!doctype html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!--<title>{{ config('app.name', 'Laravel') }}</title>-->
    <title>@yield('title')Rilan Tool Supply</title>

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <!--<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">-->
    @livewireAssets
</head>

<body class="h-screen antialiased leading-none bg-white">
    @inject('Product','App\Product')
    @inject('Category','App\Product_Category')
    @inject('Str', 'Illuminate\Support\Str')
    @include('includes.modal')
    <popup></popup>
    <div id="app">
        <div class="flex flex-col justify-between w-full p-6 bg-green-900 top-menu md:flex-row">
            <div class="relative flex justify-between">
                <a href="{{ url('/') }}" class="flex items-center focus:outline-none"><span class="text-xl font-semibold tracking-tight text-gray-200">Rilan Tool Supply</span></a>
                <button class="flex items-center px-3 py-2 text-yellow-200 border border-yellow-200 rounded md:hidden dropdown focus:outline-none">
                    <svg class="w-3 h-3 fill-current" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <title>Menu</title>
                        <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z" />
                    </svg>
                </button>
            </div>
            <nav class="md:1/2">
                <div class="flex-grow hidden block w-full font-light top-nav md:flex md:items-center md:w-auto md:h-auto md:static">
                    <div class="text-sm md:flex-grow">
                        <a href="{{ url('/') }}" class="block mt-4 mr-4 text-gray-300 md:text-center md:inline-block md:mt-0 hover:text-gray-200">
                            Home
                        </a>
                        <a href="{{ url('/track_order') }}" class="block mt-4 mr-4 text-gray-300 md:text-center md:inline-block md:mt-0 hover:text-gray-200">
                            Track Order
                        </a>
                        <a href="{{ url('/products/') }}" class="block mt-4 mr-4 text-gray-300 md:text-center md:inline-block md:mt-0 hover:text-gray-200">
                            Product
                        </a>
                        <a href="{{ url('/#contact') }}" class="block mt-4 mr-4 text-gray-300 md:text-center md:inline-block md:mt-0 hover:text-gray-200">
                            Contact
                        </a>
                        <a href="{{ url('/#about') }}" class="block mt-4 mr-4 text-gray-300 md:text-center md:inline-block md:mt-0 hover:text-gray-200">
                            About
                        </a>
                        <a href="{{ url('/faq') }}" class="block mt-4 mr-4 text-gray-300 md:text-center md:inline-block md:mt-0 hover:text-gray-200">
                            FAQ
                        </a>
                    </div>
                </div>
            </nav>
        </div>
        <!--<div class="z-50 xl:sticky xl:top-0">-->
        <div class="flex flex-col-reverse justify-between w-full bg-yellow-400 md:flex-row">
            <product-dropdown></product-dropdown>
            @if (!Request::is('products'))
            <div class="relative z-50 flex w-full border-4 border-yellow-400 rounded md:mr-3 md:w-1/4">
                @livewire('productsearch')
            </div>
            @endif
        </div>
        <div class="relative">
            @if (!Request::is('shopping_cart*'))
            <cart-notice></cart-notice>
            @endif
            <div class="container flex flex-col my-2 md:my-6">
                @yield('content')

            </div>

        </div>
        <div class="px-20 py-10 bg-green-900 footer">
            <div class="container flex flex-wrap w-full md:flex-no-wrap">
                <div class="flex-none w-full text-gray-200 md:w-1/4">
                    <h1 class="font-semibold text-center md:text-left">MENU</h1>
                    <ul class="my-5 text-center md:text-left">
                        <li class="mb-3"><a href="{{ url('/') }}">Home</a></li>
                        <li class="mb-3"><a href="{{ url('/track_order') }}">Track Order</a></li>
                        <li class="mb-3"><a href="{{ url('/products') }}">Products</a></li>
                        <li class="mb-3"><a href="{{ url('/#contact') }}">Contact</a></li>
                        <li class="mb-3"><a href="{{ url('/#about') }}">About</a></li>
                        <li class="mb-3"><a href="{{ url('/faq') }}">FAQ</a></li>
                    </ul>
                </div>
                <div class="flex-none w-full mt-5 text-gray-200 md:mt-0 md:w-1/4">
                    <h1 class="font-semibold text-center md:text-left">CATEGORY</h1>
                    <ul class="my-5 text-center md:text-left">

                        @foreach ($Category::all() as $category)
                        <li class="mb-3"><a href="{{ url('/products/category').'/'.$category->id.'-'.$Str::slug($category->category_name) }}">{{$category->category_name}}</a></li>
                        @endforeach
                    </ul>
                </div>
                <div class="flex-none w-full mt-20 text-center text-gray-200 md:w-2/4 md:text-right md:mt-0">
                    <h1 class="text-2xl font-semibold text-gray-200">Rilan Tool Supply</h1>
                    <p class="mt-2 text-sm leading-relaxed">#32 Sauyo Road, Sauyo, Novaliches, Quezon City<br>
                        Contact No.: 0938-735-7344 / 0975-434-9910</p>
                    <div class="flex flex-col mt-10 text-gray-200">
                        <span class="block font-semibold md:mb-3">Follow Us</span>
                        <div class="flex flex-row justify-center py-2 md:justify-end md:p-0">
                            <a href="https://facebook.com/juanmarco.tudilla" class="p-2 text-2xl"><i class="inline fab fa-facebook-square" aria-hidden="true"></i></a>
                            <span class="p-2 text-2xl"><i class="inline fab fa-twitter-square" aria-hidden="true"></i></span>
                            <span class="p-2 text-2xl"><i class="inline fab fa-instagram" aria-hidden="true"></i></span>
                            <span class="p-2 text-2xl"><i class="inline fab fa-youtube" aria-hidden="true"></i></span>

                        </div>

                    </div>
                </div>
            </div>
            <div class="mt-20 text-xs text-center text-gray-200 md:mt-0 md:text-left">&copy; Rilan Tool Supply 2019. All Rights Reserved.</div>
        </div>



    </div>
    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}"></script>
    @yield('ext_script')
</body>