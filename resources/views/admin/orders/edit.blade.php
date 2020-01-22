@extends('layouts.admin')

@section('content')
    @php($total = $order->items->map(function ($x) {return $x->price * $x->quantity;})->sum())
    <div class="container-fluid px-md-5" id="editor">
        <form action="{{ route('admin.orders.update', $order->hash) }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-lg-9 col-xl-10">
                    <div class="card shadow-sm mb-3">
                        <div class="card-header">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="general-tab" data-toggle="tab" href="#general"
                                       role="tab"
                                       aria-controls="general" aria-selected="true">General</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="tracking-tab" data-toggle="tab" href="#tracking"
                                       role="tab"
                                       aria-controls="tracking" aria-selected="true">Tracking / Shipping</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="general" role="tabpanel"
                                     aria-labelledby="general-tab">
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
                                                <td>
                                                    <a href="{{ route('products.view', $item->slug) }}">{{ $item->name }}</a>
                                                </td>
                                                <td>{{ $item->quantity }}</td>
                                                <td>&euro;{{ number_format($item->price, 2, ',', '.') }}</td>
                                                <td>
                                                    &euro;{{ number_format($item->price * $item->quantity, 2, ',', '.') }}</td>
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
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6">
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
                                        <div class="col-md-6">
                                            <div class="card shadow-sm">
                                                <div class="card-body">
                                                    <strong>Address:</strong><br>
                                                    {{ $order->address->first_name }} {{ $order->address->last_name }}<br>
                                                    {{ $order->address->address }}<br>
                                                    {{ $order->address->zip }} {{ $order->address->city }}<br>
                                                    {{ $order->address->country }}<br>
                                                    <hr>
                                                    @if ($order->status)
                                                        <table class="order-table">
                                                            <tbody>
                                                            <tr>
                                                                <th>Status:</th>
                                                                <td><a href="/about/order-status">{{ $order->status->text() }}</a></td>
                                                            </tr>
                                                            <tr>
                                                                <th>Shipping service:</th>
                                                                <td>{{ $order->status->shipper->name }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Tracking code:</th>
                                                                <td>{{ $order->status->code}}</td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    @else
                                                        <table class="order-table">
                                                            <tbody>
                                                            <tr>
                                                                <th>Status:</th>
                                                                <td><a href="/about/order-status">Unprocessed</a></td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="tracking" role="tabpanel"
                                     aria-labelledby="tracking-tab">
                                    @if (!$order->status)
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" name="show-tracking"
                                                       id="show-tracking">
                                                <label class="custom-control-label" for="show-tracking">
                                                    This order is processed and shipped
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <p>
                                                Tracking information will only be saved if the order has been processed,
                                                and
                                                is shipped
                                            </p>
                                        </div>
                                        <hr>
                                    @else
                                        <input type="text" name="show-tracking" value="on" hidden>
                                    @endif
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label for="service">Shipping Service</label>
                                            <select name="service" id="service"
                                                    class="form-control @error('service') is-invalid @enderror">
                                                @foreach($shippers as $shipper)
                                                    <option
                                                        value="{{ $shipper->id }}" {{ old('service', $order->status->shipper_id ?? null) === $shipper->id ? 'selected' : '' }}>{{ $shipper->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('service')
                                            <div class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="tracking">Tracking ID</label>
                                            <input type="text" name="tracking" id="tracking"
                                                   value="{{ old('tracking', $order->status->code ?? '') }}"
                                                   class="form-control @error('tracking') is-invalid @enderror">
                                            @error('tracking')
                                            <div class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="status">Status message</label>
                                        <select name="status" id="status"
                                                class="form-control @error('status') is-invalid @enderror">
                                            @foreach([1 => 'Processed', 2 => 'Shipped', 3 => 'Delivered', 4 => 'Cancelled', 5 => 'Returned', 6 => 'Refunded'] as $key => $value)
                                                <option
                                                    value="{{ $key }}" {{ $key === old('status', $order->status->status ?? null) ? 'selected' : '' }}>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                        @error('status')
                                        <div class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-xl-2">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h4>Actions</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <button class="btn btn-primary w-100" id="save">
                                    Save
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@stop

@push('js')
    <script>
        var app = new Vue({
            el: '#app',
            data: {}
        });
    </script>
@endpush
