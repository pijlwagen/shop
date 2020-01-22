@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-hover table-borderless">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>E-Mail</th>
                        <th>Orders</th>
                        <th>Roles</th>
                        <th>Blocked</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr>
                            <th>{{ $user->id }}</th>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->orders->count() }}</td>
                            <td>
                                <small>{{ $user->roles ? $user->roles->map(function($role) { return $role->name; })->implode(',') : 'Customer' }}</small>
                            </td>
                            <td id="user-is-blocked-{{ $user->id }}">{!! $user->blocked ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-times text-danger"></i>' !!}</td>
                            <td>
                                <a href="{{ route('admin.users.edit', $user->id) }}"
                                   class="text-warning mr-2">
                                    <i class="fa fa-edit" data-toggle="tooltip" data-placement="top"
                                       title="Edit {{ $user->name }}"></i>
                                </a>
                                <a href="#" class="text-danger mr-2" onclick="block({{ $user->id }})">
                                    <i class="fa fa-gavel" data-toggle="tooltip" data-placement="top"
                                       title="Block {{ $user->name }}"></i>
                                </a>
                                <a href="#" class="text-danger mr-2">
                                    <i class="fa fa-trash" data-toggle="tooltip" data-placement="top"
                                       title="Delete {{ $user->name }}"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {!! $users->links() !!}
            </div>
        </div>
    </div>
@stop

@push('js')
    <!-- IE friendly -->
    <script>
        function block(id) {
            event.preventDefault();
            $.post('/dashboard/users/' + id + '/block', {
                _token: '{{ csrf_token() }}'
            }).done(function (res) {
                if (res.blocked) {
                    $('#user-is-blocked-' + id).html('<i class="fa fa-check text-success"></i>')
                } else {
                    $('#user-is-blocked-' + id).html('<i class="fa fa-times text-danger"></i>')
                }
            });
        }
    </script>
@endpush
