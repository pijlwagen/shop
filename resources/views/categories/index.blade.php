@extends('layouts.app')

@section('content')
    <div class="container-fluid px-md-5">
        <h1 class="text-center">{{ $category->name }}</h1>
        <div class="row">
            <div class="col-md-5 mx-auto text-center">
                {!! $category->description !!}
            </div>
        </div>
        <hr class="my-5">
        <div class="row">
            <div class="col-md-3 col-lg-2">
                <h3>Filter</h3>
            </div>
            <div class="col-md-9 col-lg-10">
                @if ($category->products->count() === 0)
                    <h3 class="text-center">There are no products in this category.</h3>
                @else
                    <div class="row">
                        @foreach($category->products as $product)
                            <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                                <div class="card shadow-sm mb-3">
                                    <a href="{{ route('products.view', $product->slug) }}">
                                        <img src="{{ asset($product->images->first()->path) }}"
                                             alt="{{ $product->name }}"
                                             class="card-img-top" style="max-height: 250px; object-fit: cover">
                                    </a>
                                    <div class="card-body">
                                        <h2 style="font-size: 1rem" class="text-center">{{ $product->name }}</h2>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
@stop
