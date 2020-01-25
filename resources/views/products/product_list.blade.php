@extends('layouts/app')
@section('title')
Products -
@endsection
@section('content')
<div class="flex flex-col md:flex-no-wrap w-full">
		<h1 class="block w-full text-center text-2xl md:text-4xl md:font-thin">Products</h1>
    <product-search></product-search>

</div>

@endsection