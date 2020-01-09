@extends('layouts.app')

@section('content')
    <div class="container mt-5 pt-5">
        <div class="row justify-content-center">
            <div class="col-md-4 my-auto">
                <form method="POST" action="{{ route('register') }}">
                    <h1 class="text-center mb-3">Register</h1>
                    @csrf
                    <div class="form-group">
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               placeholder="Username" value="{{ old('name') }}">
                        @error('name')
                        <div class="invalid-feedback" role="alert">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               placeholder="E-mail address" value="{{ old('email') }}">
                        @error('name')
                        <div class="invalid-feedback" role="alert">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" placeholder="Password"
                               class="form-control @error('password') is-invalid @enderror">
                        @error('password')
                        <div class="invalid-feedback" role="alert">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <input type="password" name="password_confirmation" placeholder="Repeat password"
                               class="form-control @error('password_confirmation') is-invalid @enderror">
                        @error('password_confirmation')
                        <div class="invalid-feedback" role="alert">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary w-100">
                            Register
                        </button>
                    </div>
                    <div class="form-group">
                        Already have an account? <a href="{{ route('login') }}">Login</a> instead.
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
