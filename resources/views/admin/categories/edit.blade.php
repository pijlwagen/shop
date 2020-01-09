@extends('layouts.admin')

@section('content')
    <div class="container-fluid px-md-5">
        <div class="card shadow-sm">
            <div class="card-header">
                <h4>New category</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="name"><i class="fa fa-tag"></i> Name</label>
                        <input type="text" name="name" id="name" placeholder="My Category"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $category->name) }}" v-model="name">
                        @error('name')
                        <div class="invalid-feedback" role="alert">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="description"><i class="fa fa-pen-fancy"></i> Description</label>
                        <textarea name="description" id="description" cols="30" rows="5"
                                  class="form-control @error('description') is-invalid @enderror">{{ old('description', $category->description) }}</textarea>
                        @error('description')
                        <div class="invalid-feedback" role="alert">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group row">
                        <div class="col-7">
                            <div class="custom-control custom-checkbox mr-sm-2">
                                <input type="checkbox" class="custom-control-input" name="hide" id="hide" {{ $category->hidden ? 'checked' : '' }}>
                                <label class="custom-control-label" for="hide">Hide this category from the
                                    filter</label>
                            </div>
                        </div>
                        <div class="col-5 ml-auto">
                            <button class="btn btn-primary float-right">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
