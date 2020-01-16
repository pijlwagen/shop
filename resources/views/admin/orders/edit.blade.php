@extends('layouts.admin')

@section('content')
    @php($total = $order->items->map(function ($x) {return $x->price * $x->quantity;})->sum())
    <div class="container-fluid px-md-5" id="editor">
        {{--        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">--}}
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
        {{--        </form>--}}
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
