<div class="card shadow-sm">
    <div class="card-body">
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary float-right">New product</a>
        @if (count($products) > 0)
            <table class="table table-hover table-borderless">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>SEO</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($products as $product)
                    <tr>
                        <th>{{ $product->id }}</th>
                        <td>{{ $product->name }}</td>
                        <td>&euro;{{ number_format($product->price , 2, ',', '.')}}</td>
                        <td>{{ $product->stock }}</td>
                        <td>{!! $product->seo ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-times text-danger"></i>' !!}</td>
                        <td>
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="text-warning mr-2">
                                <i class="fa fa-edit"></i>
                            </a>
                            <a href="#" class="text-danger" data-toggle="modal"
                               data-target="#delete-{{ $product->id }}">
                                <i class="fa fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            @if ($products instanceof \Illuminate\Pagination\LengthAwarePaginator)
                {!! $products->links() !!}
            @endif
        @else
            <div class="row d-flex text-center">
                <div class="col-auto m-auto">
                    <h1><i class="fas fa-surprise"></i></h1>
                    <p>There are no products.</p>
                </div>
            </div>
        @endif
    </div>
</div>
@foreach($products as $product)
    <div class="modal fade" id="delete-{{ $product->id }}" tabindex="-1" role="dialog"
         aria-labelledby="delete-{{ $product->id }}" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete product {{ $product->name }}?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    You are about to delete the product <b>{{ $product->name }}</b>. This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                    <form action="{{ route('admin.categories.delete', $product->id) }}" method="post">
                        @csrf
                        <button type="submit" class="btn btn-danger">Confirm</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach
