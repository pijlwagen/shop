@extends('layouts.app')

@section('content')
    <div class="container-fluid" id="cart-overview">
        <form action="{{ route('cart.update') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-lg-9 mb-3">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h1 style="font-size: 1.5rem">Cart Overview</h1>
                        </div>
                        <div class="card-body">
                            @if (Cart::count() === 0)
                            <div class="d-flex text-center">
                                <div class="m-auto">
                                    Your cart is empty
                                </div>
                            </div>
                            @else
                                @foreach($cart as $item)
                                    <div class="row">
                                        @if (count($item->images) > 0)
                                            <div class="col-auto">
                                                <a href="{{ route('products.view', $item->slug) }}" target="_blank">
                                                    <img src="{{ $item->images[0] }}" alt="{{ $item->name }}"
                                                         style="max-width: 64px" class="img-fluid rounded">
                                                </a>
                                            </div>
                                        @endif
                                        <div class="col-4">
                                            <span class="product-name"><a
                                                    href="{{ route('products.view', $item->slug) }}"
                                                    target="_blank">{{ $item->name }}</a></span><br>
                                            @if ($item->discount)
                                                <small>Price per unit:
                                                    &euro;{{ number_format(Cart::itemDiscountPrice($item->hash, false), 2, ',', '.') }}
                                                    <s class="text-danger">{{ number_format($item->price, 2, ',', '.') }}</s></small>
                                                <br>
                                            @else
                                                <small>Price per unit:
                                                    &euro;{{ number_format(Cart::itemDiscountPrice($item->hash, false), 2, ',', '.') }}</small>
                                                <br>
                                            @endif
                                            <small class="product-options text-muted">
                                                @foreach($item->options ?? [] as $option)
                                                    {{ $option->title }}
                                                    : {{ $option->value }} {!! $option->increment ? '&euro;' . number_format($option->increment, 2, ',', '.') : '' !!}
                                                    <br>
                                                @endforeach
                                            </small>
                                            <small class="item-number text-muted">
                                                Item number: 293{{ $item->id }}<br>
                                            </small>
                                            <small class="product-price">
                                            </small>
                                        </div>
                                        <div class="col-auto ml-auto d-flex">
                                                <span class="total my-auto">
                                                    &euro;{{ number_format(Cart::itemDiscountPrice($item->hash) * $item->quantity, 2, ',', '.') }}
                                                </span>
                                        </div>
                                        <div class="col-2">
                                            <label for="quantity-{{ $item->id }}">Quantity</label>
                                            <input type="number"
                                                   class="form-control @error('quantity.' . $item->hash) is-invalid @enderror"
                                                   id="quantity-{{ $item->id }}"
                                                   name="quantity[{{ $item->hash }}]"
                                                   value="{{ old("quantity['$item->hash']", $item->quantity) }}">
                                            @error('quantity.' . $item->hash)
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <hr>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h1 style="font-size: 1.5rem">Cart Summary</h1>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6 text-left">
                                    <p>Total</p>
                                </div>
                                <div class="col-6 text-right">
                                    &euro;{{ number_format(Cart::subTotal(), 2, ',', '.') }}
                                </div>
                            </div>
                            <button class="btn btn-primary w-100 mb-3" v-bind:disabled="errors.size != 0"
                                    v-bind:class="{disabled: errors.size != 0}">Update cart
                            </button>
                            <a href="{{ route('cart.checkout') }}" class="btn btn-success w-100">Checkout</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@stop

{{--@push('js')--}}
{{--    <script>--}}
{{--        var app = new Vue({--}}
{{--            el: '#cart-overview',--}}
{{--            data: {--}}
{{--                items: [],--}}
{{--                errors: new Map(),--}}
{{--            },--}}
{{--            created: function () {--}}
{{--                this.items = JSON.parse("{{ json_encode($cart) }}".replace(/&quot;/g, '"'))--}}
{{--            },--}}
{{--            methods: {--}}
{{--                total: function (index, floatOnly = false) {--}}

{{--                },--}}
{{--                cart: function () {--}}
{{--                    var total = 0;--}}
{{--                    Object.keys(this.items).forEach(function (item) {--}}
{{--                        total += this.total(item, true);--}}
{{--                    }.bind(this));--}}
{{--                    return this.number_format(total, 2, ',', '.')--}}
{{--                },--}}
{{--                number_format: function (number, decimals, dec_point, thousands_sep) {--}}
{{--                    // Strip all characters but numerical ones.--}}
{{--                    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');--}}
{{--                    var n = !isFinite(+number) ? 0 : +number,--}}
{{--                        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),--}}
{{--                        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,--}}
{{--                        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,--}}
{{--                        s = '',--}}
{{--                        toFixedFix = function (n, prec) {--}}
{{--                            var k = Math.pow(10, prec);--}}
{{--                            return '' + Math.round(n * k) / k;--}}
{{--                        };--}}
{{--                    // Fix for IE parseFloat(0.55).toFixed(0) = 0;--}}
{{--                    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');--}}
{{--                    if (s[0].length > 3) {--}}
{{--                        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);--}}
{{--                    }--}}
{{--                    if ((s[1] || '').length < prec) {--}}
{{--                        s[1] = s[1] || '';--}}
{{--                        s[1] += new Array(prec - s[1].length + 1).join('0');--}}
{{--                    }--}}
{{--                    return s.join(dec);--}}
{{--                }--}}
{{--            },--}}
{{--            delimiters: ['<%', '%>']--}}
{{--        });--}}
{{--    </script>--}}
{{--@endpush--}}
