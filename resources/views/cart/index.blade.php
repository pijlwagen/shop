@extends('layouts.app')

@section('content')
    <div class="container-fluid" id="cart-overview">
        <div class="row">
            <div class="col-lg-11 col-xl-10 mx-auto">
                <div class="row">
                    <div class="col-lg-9 mb-3">
                        <div class="card shadow-sm">
                            <div class="card-header">
                                <h1 style="font-size: 1.5rem">Cart Overview</h1>
                            </div>
                            <div class="card-body">
                                @foreach($cart as $item)
                                    <div class="row">
                                        @if (count($item->images) > 0)
                                            <div class="col-auto">
                                                <a href="{{ route('products.view', $item->slug) }}" target="_blank">
                                                    <img src="{{ asset($item->images[0]) }}" alt="{{ $item->name }}"
                                                         class="img-fluid rounded checkout-product-image">
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <span class="product-name"><a
                                                        href="{{ route('products.view', $item->slug) }}"
                                                        target="_blank">{{ $item->name }}</a></span><br>
                                                <small class="product-options">
                                                    {{ implode(', ', array_map(function($x){return "{$x['title']}: {$x['value']}";}, $item->options)) }}
                                                    <br>
                                                </small>
                                                <small class="item-number">
                                                    Item number: 293{{ $item->id }}<br>
                                                </small>
                                                <small>
                                                    @if ($item->discount)
                                                        Price per unit:
                                                        &euro;{{ number_format($item->discount(), 2, ',', '.') }} <span
                                                            class="text-danger"><s>&euro;{{ number_format($item->price, 2, ',', '.') }}</s></span>
                                                    @endif
                                                </small>
                                            </div>
                                            <div class="col-auto ml-auto d-flex">
                                                <span class="total my-auto">
                                                    &euro;<% total('{{ $item->hash }}') %>
                                                </span>
                                            </div>
                                            <div class="col-2">
                                                <label for="quantity-{{ $item->hash }}">Quantity</label>
                                                <input type="number" class="form-control"
                                                       id="quantity-{{ $item->hash }}"
                                                       name="quantity" value="1" min="0"
                                                       v-model="items['{{ $item->hash }}'].quantity">
                                            </div>
                                        @else
                                            <div class="col-9"></div>
                                            <div class="col-3"></div>
                                        @endif
                                    </div>
                                    <hr>
                                @endforeach
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
                                        &euro;<% cart() %>
                                    </div>
                                </div>
                                <button class="btn btn-primary w-100 mb-3" v-bind:disabled="errors.size != 0"
                                        v-bind:class="{disabled: errors.size != 0}">Update cart
                                </button>
                                <a href="#" class="btn btn-success w-100">Checkout</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@push('js')
    <script>
        var app = new Vue({
            el: '#cart-overview',
            data: {
                items: [],
                errors: new Map(),
            },
            created: function () {
                this.items = JSON.parse("{{ json_encode($cart) }}".replace(/&quot;/g, '"'))
            },
            methods: {
                total: function (index, floatOnly = false) {
                    var item = this.items[index];
                    var result = 0;
                    if (item.discount) {
                        if (item.discount.type === 'free') {
                            result = 0;
                        } else if (item.discount.type === 'fixed') {
                            result = item.price * item.quantity - (item.quantity * item.discount.amount);
                        } else {
                            result = (item.price - (item.price / 100 * item.discount.amount)) * item.quantity
                        }
                    } else {
                        result = item.price * item.quantity;
                    }
                    return floatOnly ? result : this.number_format(result, 2, ',', '.')
                },
                cart: function () {
                    var total = 0;
                    Object.keys(this.items).forEach(function (item) {
                        total += this.total(item, true);
                    }.bind(this));
                    return this.number_format(total, 2, ',', '.')
                },
                number_format: function (number, decimals, dec_point, thousands_sep) {
                    // Strip all characters but numerical ones.
                    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
                    var n = !isFinite(+number) ? 0 : +number,
                        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
                        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
                        s = '',
                        toFixedFix = function (n, prec) {
                            var k = Math.pow(10, prec);
                            return '' + Math.round(n * k) / k;
                        };
                    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
                    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
                    if (s[0].length > 3) {
                        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
                    }
                    if ((s[1] || '').length < prec) {
                        s[1] = s[1] || '';
                        s[1] += new Array(prec - s[1].length + 1).join('0');
                    }
                    return s.join(dec);
                }
            },
            delimiters: ['<%', '%>']
        });
    </script>
@endpush
