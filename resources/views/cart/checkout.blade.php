@extends('layouts.app')

@section('content')
    <div class="container-fluid" id="order-review">
        <div class="row">
            <div class="col-lg-11 col-xl-10 mx-auto">
                <div class="row">
                    <div class="col-md-7 mb-3">
                        @guest
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h1 style="font-size: 1.5rem">Shipping Information</h1>

                                </div>
                            </div>
                        @else
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h1 style="font-size: 1.5rem">Shipping Information</h1>

                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-5">
                        <div class="card shadow-sm">
                            <div class="card-header">
                                <h1 style="font-size: 1.5rem">Order Summary</h1>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach($cart as $item)
                                        @if (count($item->images) > 0)
                                            <div class="col-3">
                                                <a href="{{ route('products.view', $item->slug) }}" target="_blank">
                                                    <img src="{{ asset($item->images[0]) }}" alt="{{ $item->name }}"
                                                         class="img-fluid rounded checkout-product-image">
                                                </a>
                                            </div>
                                            <div class="col-6">
                                                <span class="text-primary mr-2">{{ $item->quantity }}x</span> <span
                                                    class="product-name"><a href="{{ route('products.view', $item->slug) }}" target="_blank">{{ $item->name }}</a></span><br>
                                                <small class="product-options">
                                                    {{ implode(', ', array_map(function($x){return "{$x['title']}: {$x['value']}";}, $item->options)) }}
                                                </small>
                                            </div>
                                            <div class="col-3">
                                                <span class="total">
                                                    &euro;{{ number_format($item->total(), 2, ',', '.') }}
                                                </span>
                                            </div>
                                        @else
                                            <div class="col-9"></div>
                                            <div class="col-3"></div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
