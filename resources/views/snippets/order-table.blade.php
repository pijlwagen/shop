<div class="card shadow-sm">
    <div class="card-body">
        @if (count($orders) > 0)
            <table class="table table-hover table-borderless">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Status</th>
                    <th>Paid</th>
                    <th>Items</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($orders as $order)
                    <tr>
                        <th>{{ 4322 + $order->id }}</th>
                        <td>{{ $order->status ? $order->status->text() : 'Unprocessed' }}</td>
                        <td>{!! $order->payment ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-times text-danger"></i>' !!}</td>
                        <td>{{ $order->items->sum('quantity') }}</td>
                        <td>
                            <a href="{{ route('admin.orders.edit', $order->hash) }}" class="text-warning"><i class="fa fa-edit"></i></a>
{{--                            <a href="{{ route('admin.products.edit', $product->id) }}" class="text-warning mr-2">--}}
{{--                                <i class="fa fa-edit"></i>--}}
{{--                            </a>--}}
{{--                            <a href="#" class="text-danger" data-toggle="modal"--}}
{{--                               data-target="#delete-{{ $product->id }}">--}}
{{--                                <i class="fa fa-trash"></i>--}}
{{--                            </a>--}}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            @if ($orders instanceof \Illuminate\Pagination\LengthAwarePaginator)
                {!! $orders->links() !!}
            @endif
        @else
            <div class="row d-flex text-center">
                <div class="col-auto m-auto">
                    <h1><i class="fas fa-surprise"></i></h1>
                    <p>There are no orders.</p>
                </div>
            </div>
        @endif
    </div>
</div>
