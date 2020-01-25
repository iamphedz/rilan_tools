@extends('layouts.app')
@section('title')
Cart Checkout -
@endsection

@section('content')
<cart-checkout session_id="{{ $session_id }}"></cart-checkout>
@endsection