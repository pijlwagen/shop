@extends('layouts.app')

@push('head')
    <link rel="stylesheet" href="{{ asset('libs/fotorama/fotorama.css') }}">
@endpush

@section('content')
    <div class="container">
        <div class="row">
            @if ($product->images->first())
                <div class="col-md-5">
                    <div class="fotorama" data-nav="thumbs">
                        @foreach($product->images as $image)
                            <img src="{{ asset($image->path) }}" alt="{{ $product->name }}">
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="col-md-7">
                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                    @csrf
                    <h2 class="text-muted" style="font-size: 14px">{{ config('app.name') }}</h2>
                    <h1>{{ $product->name }}</h1>
                    @php($discount = $product->discounts->first())
                    @if ($discount)
                        @if ($discount->type == 'free')
                            <h3>FREE <span
                                    class="text-danger"><s>&euro;<% number_format(price(), 2, ',', '.')  %></s></span>
                            </h3>
                        @elseif ($discount->type == 'fixed')
                            <h3>&euro;<% number_format(price() - product.discounts[0].amount, 2, ',', '.') %> <span
                                    class="text-danger"><s>&euro;<% number_format(price(), 2, ',', '.')  %></s></span>
                            </h3>
                        @elseif ($discount->type == 'percentage')
                            <h3>&euro;<% number_format(price() - (price() / 100 * product.discounts[0].amount), 2, ',',
                                '.') %> <span
                                    class="text-danger"><s>&euro;<% number_format(price(), 2, ',', '.')  %></s></span>
                            </h3>
                        @endif
                    @else
                        <h3>&euro;<% number_format(price, 2, ',', '.') %></h3>
                    @endif
                    <p>Item number: 293{{ $product->id }}</p>
                    <hr>
                    @if ($product->options->first())
                        <div class="form-group row" v-for="(option, index) in product.options" v-bind:key="option.id">
                            <div class="col-sm-7">
                                <label v-bind:for="'option-' + option.id"><% option.title %></label>
                                <select v-bind:name="'option['+ option.id +']'" class="form-control"
                                        v-bind:id="'option-' + option.id" v-model="options[index].value">
                                    <option v-for="value in option.values" v-bind:key="value.id"
                                            v-bind:value="value.id"><% value.value %> <% value.increment ? '+' +
                                        number_format(value.increment, 2, ',', '.') : '' %>
                                    </option>
                                </select>
                            </div>
                        </div>
                    @endif
                    <div class="form-group row">
                        <div class="col-sm-7">
                            <label for="quantity">Quantity <small class="text-muted">({{ $product->stock }} in
                                    stock)</small></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <button class="btn btn-secondary"><i class="fa fa-minus"></i></button>
                                </div>
                                <input type="number" class="form-control" id="quantity" name="quantity" value="1"
                                       v-model="quantity" v-bind:class="{'is-invalid': errors.has('quantity')}">
                                <div class="input-group-append">
                                    <button class="btn btn-secondary"><i class="fa fa-plus"></i></button>
                                </div>
                            </div>
                            <small class="text-danger" v-if="errors.has('quantity')">
                                <% errors.get('quantity') %>
                            </small>
                        </div>
                    </div>
                    @if ($product->stock > 0)
                        <button class="btn btn-primary w-100">Add to cart</button>
                    @else
                        <button class="btn btn-primary w-100 disabled" disabled>None in stock</button>
                    @endif
                </form>
            </div>
        </div>
        <hr>
        <h2>Product information</h2>
        {!! $product->description !!}
    </div>
@stop

@push('js')
    <script src="{{ asset('libs/fotorama/fotorama.js') }}"></script>
    <script>
        var app = new Vue({
            el: '#app',
            data: {
                quantity: 1,
                options: []
            },
            watch: {
                quantity: function () {
                    if (!isNaN(this.quantity)) {
                        if (this.quantity > this.product.stock) this.errors.set('quantity', 'Unfortunately we don\'t have enough of this product left in stock.');
                        else if (this.errors.has('quantity')) this.errors.delete('quantity');
                    }
                },
            },
            methods: {
                price: function () {
                    let price = this.product.price;
                    this.options.map(x => this.product.options.filter(y => y.id === x.id)[0].values.filter(y => y.id === x.value)[0].increment).filter(x => x).forEach(x => {
                        price += x;
                    });
                    return price;
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
            created: function () {
                this.errors = new Map();
                this.product = JSON.parse('{{ json_encode($product) }}'.replace(/&quot;/g, '"'));
                @foreach($product->options as $option)
                    this.options.push({
                    id: {{ $option->id }},
                    value: {{ $option->values->first()->id }},
                })
                @endforeach
            },
            delimiters: ['<%', '%>']
        })
    </script>
@endpush
