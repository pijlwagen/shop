@extends('layouts.admin')

@section('content')
    <div class="container-fluid px-md-5">
        <div class="card shadow-sm">
            <div class="card-header">
                Editing user: {{ $user->name }}
            </div>
            <div class="card-body">
                <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                    @csrf
                    <div class="form-group row">
                        <div class="col-lg-4 mb-3">
                            <label for="name"><i class="fa fa-user"></i> Username</label>
                            <input type="text" name="name" id="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $user->name) }}">
                            @error('name')
                            <div class="invalid-feedback" role="alert">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-lg-4 mb-3">
                            <label for="email"><i class="fa fa-envelope"></i> E-Mail address</label>
                            <input type="text" name="email" id="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $user->email) }}">
                            @error('email')
                            <div class="invalid-feedback" role="alert">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-lg-4 mb-3">
                            <label for="password"><i class="fa fa-key"></i> Password</label>
                            <input type="text" name="password" id="password"
                                   class="form-control @error('password') is-invalid @enderror">
                            <small class="text-muted">Leave empty if user does not need a new password.</small>
                            @error('password')
                            <div class="invalid-feedback" role="alert">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="roles"><i class="fa fa-user-tag"></i> Roles</label>
                        <select name="roles[]" id="roles" class="form-control @error('roles') is-invalid @enderror"
                                multiple>
                            <option disabled selected>Customer</option>
                            @foreach($roles as $role)
                                <option
                                    value="{{ $role->id }}" {{ $user->roles->contains('name', $role->name) ? 'selected' : '' }}>{{ $role->name }}</option>
                            @endforeach
                        </select>
                        @error('roles')
                        <div class="invalid-feedback" role="alert">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group pb-5">
                        <div class="custom-control custom-checkbox mr-sm-2">
                            <input type="checkbox" class="custom-control-input"
                                   id="block" {{ $user->blocked ? 'checked' : '' }}>
                            <label class="custom-control-label" for="block">Block this user</label>
                        </div>
                        <button class="btn btn-primary float-right">Update</button>
                    </div>
                </form>
                <hr>
                <h3>Orders</h3>
                @if ($user->orders->first())
                    <table class="table table-hover table-borderless">
                        <tbody>
                        @foreach($user->orders as $order)
                            <tr>
                                <td><a href="{{ route('admin.orders.edit', $order->hash) }}">{{ 4322 + $order->id }}</a></td>
                                <td class="w-100"><a href="{{ route('admin.orders.edit', $order->hash) }}">{{ $order->hash }}</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="d-flex text-center">
                        <div class="m-auto">
                            <h1>
                                <i class="fa fa-clipboard"></i>
                            </h1>
                            <p>
                                This user has no orders.
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@stop

@push('js')
    <script>
        $('#block').on('change', function () {
            $.post('{{ route('admin.users.block', $user->id) }}', {
                _token: '{{ csrf_token() }}'
            }).done(function (res) {
                console.log('Blocked: ' + res.blocked)
            })
        });
    </script>
@endpush
