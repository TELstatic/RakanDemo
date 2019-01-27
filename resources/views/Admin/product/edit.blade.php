@extends('layouts.Admin.home')

@section('content')
    <div class="content-wrapper">
        <div id="app">
            <product-edit :product="{{$product}}"></product-edit>
        </div>
    </div>
@endsection
