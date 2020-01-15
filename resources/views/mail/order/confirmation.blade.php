@extends('layouts.mail')

@section('content')
    <br>

    <div class="title" style="font-family:Helvetica, Arial, sans-serif;font-size:18px;font-weight:600;color:#374550">
        Order #{{ 4322 + $order->id }}</div>
    <br>

    <div class="body-text"
         style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333">
        You have successfully placed a new order with us.
        <br><br>

        You will receive an email when we process your order. If this transaction is unauthorized please contact our
        support at
        <a href="#">{{ config('app.mail') }}</a>
        <br><br>

        <strong style="font-size: 17px">Order Items</strong><br>
        <table style="margin-top: 10px; ">
            <thead>
            <tr>
                <th>Item</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
            </thead>
            @foreach($order->items as $item)
                <tr>
                    <td style="width:100%"><a href="{{ route('products.view', $item->slug) }}" target="_blank" style="color: #2a2a2a; text-decoration: none">{{ $item->name }}</a></td>
                    <td style="min-width: 20px; padding-right: 10px">{{ $item->quantity }}</td>
                    <td style="min-width: 20px; padding-right: 10px">&euro;{{ number_format($item->price, 2, ',', '.') }}</td>
                    <td>&euro;{{ number_format($item->price * $item->quantity, 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </table>
        <br><br>
        @php($total = $order->items->map(function ($x) {return $x->price * $x->quantity;})->sum())
        <strong style="font-size: 17px">Order Information</strong><br>
        <table style="margin-top: 10px">
            <tr>
                <th style="padding-right: 20px">Reference #:</th>
                <td>432{{ $order->id }}</td>
            </tr>
            <tr>
                <th style="padding-right: 20px">Reference ID:</th>
                <td>{{ $order->hash }}</td>
            </tr>
            <tr>
                <th style="padding-right: 20px">Date:</th>
                <td>{{ \Carbon\Carbon::parse($order->createdAt)->format('j M Y')  }}</td>
            </tr>
            <tr>
                <th style="padding-right: 20px">Shipping Method:</th>
                <td>Free Worldwide Shipping</td>
            </tr>
            <tr>
                <th style="padding-right: 20px">Payment Method:</th>
                <td>{{ Helper::paymentMethod($order->payment->method) }}</td>
            </tr>
            <tr>
                <th style="padding-right: 20px">Subtotal</th>
                <td>
                    @php($subTotal = $total - ($total  / 100 * 21))
                    &euro;{{ number_format($subTotal, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <th style="padding-right: 20px">
                    <a href="https://www.belastingdienst.nl/wps/wcm/connect/bldcontenten/belastingdienst/business/vat/vat_in_the_netherlands/calculating_vat/vat_tariffs"
                       style="color: #212529" target="_blank">VAT (21%)</a></th>
                <td>
                    @php($vat = $total / 100 * 21)
                    &euro;{{ number_format($vat, 2, ',', '.') }}</td>
            </tr>
            @if ($order->payment->method === 'creditcard')
                <tr>
                    <th style="padding-right: 20px">Credit Card +2.0%</th>
                    @php($transaction = $total / 100 * 2)
                    <td>&euro;{{ number_format($transaction ,2, ',', '.') }}</td>
                </tr>
                <tr>
                    <th style="padding-right: 20px">Total</th>
                    <td>&euro;{{ number_format($vat + $subTotal + $transaction, 2, ',', '.') }}</td>
                </tr>
            @else
                <tr>
                    <th style="padding-right: 20px">Total</th>
                    <td>&euro;{{ number_format($vat + $subTotal, 2, ',', '.') }}</td>
                </tr>
            @endif
        </table>

        <br>
        <strong>Address:</strong><br>
        {{ $order->address->first_name }} {{ $order->address->last_name }}<br>
        {{ $order->address->address }}<br>
        {{ $order->address->zip }} {{ $order->address->city }}<br>
        {{ $order->address->country }}<br>
    </div>
    </div>
@stop
