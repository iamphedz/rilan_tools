@extends('layouts.admin_app')
@section('content')
<admin-panel :user="{{ Auth::user() }}"></admin-panel>
@endsection