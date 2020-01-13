@extends('layouts.app')

@section('content')
    <div class="container-fluid px-md-5">
        <h1 class="text-center">Order #432{{ $order->id }}</h1>
        <hr class="my-5">
        <div class="row mb-5">
            <div class="col-md-auto mb-5">
                @php($total = $order->items->map(function ($x) {return $x->price * $x->quantity;})->sum())
                <h3>Order information</h3>
                <div class="card shadow-sm">
                    <div class="card-body">
                        <table class="order-table">
                            <tr>
                                <th>Reference #:</th>
                                <td>432{{ $order->id }}</td>
                            </tr>
                            <tr>
                                <th>Reference ID:</th>
                                <td>{{ $order->hash }}</td>
                            </tr>
                            <tr>
                                <th>Date:</th>
                                <td>{{ \Carbon\Carbon::parse($order->createdAt)->format('j M Y')  }}</td>
                            </tr>
                            <tr>
                                <th>Shipping Method:</th>
                                <td>Free Worldwide Shipping</td>
                            </tr>
                            <tr>
                                <th>Payment Method:</th>
                                <td>{{ Helper::paymentMethod($order->payment->method) }}</td>
                            </tr>
                            <tr>
                                <th>Subtotal</th>
                                <td>
                                    @php($subTotal = $total - ($total  / 100 * 21))
                                    &euro;{{ number_format($subTotal, 2, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th>
                                    <a href="https://www.belastingdienst.nl/wps/wcm/connect/bldcontenten/belastingdienst/business/vat/vat_in_the_netherlands/calculating_vat/vat_tariffs"
                                       style="color: #212529" target="_blank">VAT (21%)</a></th>
                                <td>
                                    @php($vat = $total / 100 * 21)
                                    &euro;{{ number_format($vat, 2, ',', '.') }}</td>
                            </tr>
                            @if ($order->payment->method === 'creditcard')
                                <tr>
                                    <th>Credit Card +2.0%</th>
                                    @php($transaction = $total / 100 * 2)
                                    <td>&euro;{{ number_format($transaction ,2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th>Total</th>
                                    <td>&euro;{{ number_format($vat + $subTotal + $transaction, 2, ',', '.') }}</td>
                                </tr>
                            @else
                                <tr>
                                    <th>Total</th>
                                    <td>&euro;{{ number_format($vat + $subTotal, 2, ',', '.') }}</td>
                                </tr>
                            @endif
                        </table>

                    </div>
                </div>
            </div>
            <div class="col-lg-4 ml-auto mb-5">
                <h3>Shipping information</h3>
                <div class="card shadow-sm">
                    <div class="card-body">
                        <strong>Address:</strong><br>
                        {{ $order->address->first_name }} {{ $order->address->last_name }}<br>
                        {{ $order->address->address }}<br>
                        {{ $order->address->zip }} {{ $order->address->city }}<br>
                        {{ $order->address->country }}<br>
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <table class="table table-striped table-borderless">
                    <thead>
                    <tr>
                        <th class="w-50">Item</th>
                        <th>Quantity</th>
                        <th>Price per</th>
                        <th>Subtotal</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td><a href="{{ route('products.view', $item->slug) }}">{{ $item->name }}</a></td>
                            <td>{{ $item->quantity }}</td>
                            <td>&euro;{{ number_format($item->price, 2, ',', '.') }}</td>
                            <td>&euro;{{ number_format($item->price * $item->quantity, 2, ',', '.') }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td></td>
                        <td></td>
                        <td>Total:</td>
                        <td>
                            &euro;{{ number_format($total, 2, ',', '.') }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop
