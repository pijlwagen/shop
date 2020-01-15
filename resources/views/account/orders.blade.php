@extends('layouts.app')

@section('content')
    <div class="container-fluid px-md-5">
        <h1 class="mb-5">My Account</h1>
        <div class="row text-center justify-content-center">
            <div class="col-6 col-md-5 col-lg-4">
                <a href="{{ route('account.index') }}">Personal Information</a>
            </div>
            <div class="col-6 col-md-5 col-lg-4">
                <b>Orders</b>
            </div>
        </div>
        <hr class="mb-5">
        <table class="table table-borderless table-striped table-hover">
            <thead>
            <tr>
                <th>Order #</th>
                <th class="pr-4">Items</th>
                <th class="pr-4">Total</th>
                <th>Date</th>
            </tr>
            </thead>
            <tbody>
            @foreach($user->orders as $order)
                <tr>
                    <td class="w-100"><a href="{{ route('order.view', $order->hash) }}">432{{ $order->id }}</a></td>
                    <td>{{ $order->items->sum('quantity') }}</td>
                    <td>&euro;{{ number_format($order->payment->amount, 2, ',', '.') }}</td>
                    <td style="min-width: 80px">{{ \Carbon\Carbon::parse($order->created_at)->format('j M') }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@stop
