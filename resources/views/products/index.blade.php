@extends('layouts.app')

@section('content')
    <div class="container-fluid px-md-5">
        <h1 class="text-center">Inventory</h1>
        <hr class="my-5">
        <div class="row">
            <div class="col-md-3 col-lg-2">
                <h3>Categories</h3>
                @if ($categories->first())
                    <ul class="list-unstyled">
                        @foreach($categories as $category)
                            <li><a href="{{ route('categories.index', $category->slug) }}">{{ $category->name }}
                                    ({{ $category->products->count() }})</a></li>
                        @endforeach
                    </ul>
                @else
                    <span>There are no categories</span>
                @endif
            </div>
            <div class="col-md-9 col-lg-10">
                @if ($category->products->count() === 0)
                    <h3 class="text-center">There are no products <i class="fa- fa-search"></i> .</h3>
                @else
                    <div class="row">
                        @foreach($category->products as $product)
                            <div class="col-6 col-md-6 col-lg-4 col-xl-3">
                                <div class="card shadow-sm mb-3">
                                    <a href="{{ route('products.view', $product->slug) }}">
                                        @if ($product->discounts->first())
                                            <span class="badge badge-danger" style="font-size: 1rem; margin-top: 10px; margin-left: 10px; position: absolute">ON SALE</span>
                                        @endif
                                        <img src="{{ asset($product->images->first()->path) }}"
                                             alt="{{ $product->name }}"
                                             class="card-img-top" style="height: 100%; width: 100%;object-fit: cover">
                                    </a>
                                    <div class="card-body text-center">
                                        <h2 style="font-size: 1rem">{{ $product->name }}</h2>
                                        @if ($product->discounts->first())
                                            <span class="item-price" style="font-size: 1rem"><span
                                                    class="discount-price text-danger">&euro;{{ number_format(Helper::discountedPrice($product, false), 2, ',', '.') }}</span> <s class="text-muted">&euro;{{ number_format($product->price, 2, ',', '.') }}</s></span>
                                        @else
                                            <span class="item-price"
                                                  style="font-size: 1rem">&euro;{{ number_format($product->price, 2, ',', '.') }}</span>
                                        @endif
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
