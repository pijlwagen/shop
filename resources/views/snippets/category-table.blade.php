<div class="card shadow-sm">
    <div class="card-body">
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary float-right">New category</a>
        @if (count($categories) > 0)
            <table class="table table-hover table-borderless">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Hidden</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($categories as $category)
                    <tr>
                        <th>{{ $category->id }}</th>
                        <td>{{ $category->name }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($category->description, 20) }}</td>
                        <td id="hide-category-{{ $category->id }}">{!! $category->hidden ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-times text-danger"></i>' !!}</td>
                        <td>
                            <a href="{{ route('admin.categories.edit', $category->id) }}"
                               class="text-warning mr-2">
                                <i class="fa fa-edit" data-toggle="tooltip" data-placement="top"
                                   title="Edit {{ $category->name }}"></i>
                            </a>
                            <a href="#" class="text-info mr-2" v-on:click="hide({{ $category->id }})">
                                <i class="fas fa-eye-slash" data-toggle="tooltip" data-placement="top"
                                   title="Hide/show {{ $category->name }}"></i>
                            </a>
                            <a href="#" class="text-danger" data-toggle="modal"
                               data-target="#delete-{{ $category->id }}">
                                <i class="fa fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            @if ($categories instanceof \Illuminate\Pagination\LengthAwarePaginator)
                {!! $categories->links() !!}

            @endif
        @else
            <div class="row d-flex text-center">
                <div class="col-auto m-auto">
                    <h1><i class="fas fa-surprise"></i></h1>
                    <p>There are no categories.</p>
                </div>
            </div>
        @endif
    </div>
</div>
@foreach($categories as $category)
    <div class="modal fade" id="delete-{{ $category->id }}" tabindex="-1" role="dialog"
         aria-labelledby="delete-{{ $category->id }}" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete category {{ $category->name }}?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    You are about to delete the category <b>{{ $category->name }}</b>. This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                    <form action="{{ route('admin.categories.delete', $category->id) }}" method="post">
                        @csrf
                        <button type="submit" class="btn btn-danger">Confirm</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach
