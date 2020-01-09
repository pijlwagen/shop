@extends('layouts.admin')

@section('content')
    <div class="container-fluid px-md-5">
        <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
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
                                    <a class="nav-link" id="seo-tab" data-toggle="tab" href="#seo" role="tab"
                                       aria-controls="seo" aria-selected="false">SEO</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="general" role="tabpanel"
                                     aria-labelledby="general-tab">
                                    <div class="form-group">
                                        <label for="name"><i class="fa fa-tag"></i> Name</label>
                                        <input type="text" name="name" id="name" placeholder="My Category"
                                               class="form-control @error('name') is-invalid @enderror"
                                               v-model="name">
                                        @error('name')
                                        <div class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="description"><i class="fa fa-pen-fancy"></i> Description</label>
                                        <textarea name="description" id="description" cols="30" rows="5"
                                                  class="form-control @error('description') is-invalid @enderror"
                                                  v-model="description"></textarea>
                                        @error('description')
                                        <div class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="image"><i class="fa fa-image"></i> Category image</label>
                                        <input type="file" name="image" id="image"
                                               class="form-control-file @error('image') is-invalid @enderror">
                                        @error('image')
                                        <div class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-7">
                                            <div class="custom-control custom-checkbox mr-sm-2">
                                                <input type="checkbox" class="custom-control-input" name="hide"
                                                       id="hide">
                                                <label class="custom-control-label" for="hide">Hide this category from
                                                    the
                                                    filter</label>
                                            </div>
                                        </div>
                                        <div class="col-5 ml-auto">
                                            <button class="btn btn-primary float-right">Save</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="seo" role="tabpanel" aria-labelledby="seo-tab">
                                    <div class="form-group row">
                                        <div class="col-lg-12">
                                            <label for="seo-description">Meta Description</label>
                                            <textarea type="text" name="seo-description" id="seo-description"
                                                      v-bind:readonly="seo.descriptionSameAsDescription" rows="5"
                                                      class="form-control @error('seo-description') is-invalid @enderror"
                                                      v-model="seo.description"></textarea>
                                            @error('seo-description')
                                            <div class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                            <div class="custom-control custom-checkbox mr-sm-2 mt-2">
                                                <input type="checkbox" class="custom-control-input"
                                                       id="description-same-as-description"
                                                       v-model="seo.descriptionSameAsDescription">
                                                <label class="custom-control-label"
                                                       for="description-same-as-description">Meta
                                                    Description same as product description</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-lg-7">
                                            <label for="seo-title">Meta Title</label>
                                            <input type="text" name="seo-title" id="seo-title"
                                                   v-bind:readonly="seo.titleSameAsName"
                                                   class="form-control @error('seo-title') is-invalid @enderror"
                                                   value="{{ old('seo-title') }}" v-model="seo.title">
                                            @error('seo-title')
                                            <div class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                            <div class="custom-control custom-checkbox mr-sm-2 mt-2">
                                                <input type="checkbox" class="custom-control-input"
                                                       id="title-same-as-name"
                                                       v-model="seo.titleSameAsName">
                                                <label class="custom-control-label" for="title-same-as-name">Meta Title
                                                    same as
                                                    product
                                                    name</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-5">
                                            <label for="seo-keywords">Meta Keywords</label>
                                            <input type="text" name="seo-keywords" id="seo-keywords"
                                                   data-role="tagsinput"
                                                   class="form-control @error('seo-keywords') is-invalid @enderror"
                                                   value="{{ old('seo-keywords') }}">
                                            @error('seo-keywords')
                                            <div class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group mt-2">
                                        <label for="seo-image"><i class="fa fa-image"></i> Twitter/Open Graph
                                            Image</label>
                                        <input type="file" name="seo-image" id="seo-image"
                                               class="form-control-file @error('seo-image') is-invalid @enderror">
                                        @error('seo-image')
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
                                <button class="btn btn-primary w-100">
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
            data: {
                name: '',
                description: '',
                seo: {
                    title: '',
                    description: '',
                    titleSameAsName: true,
                    descriptionSameAsDescription: true
                }
            },
            watch: {
                name: function () {
                    if (!this.seo.titleSameAsName) return;
                    this.seo.title = this.name;
                },
                description: function () {
                    if (!this.seo.descriptionSameAsDescription) return;
                    this.seo.description = this.description;
                },
                seo: {
                    handler: function () {
                        if (this.seo.titleSameAsName) this.seo.title = this.name;
                        if (this.seo.descriptionSameAsDescription) this.seo.description = this.description;
                    },
                    deep: true,
                },
            },
            created: function () {
                this.name = '{{ old('name') }}';
                this.description = '{{ old('description') }}';
                this.seo.title = '{{ old('seo-title') }}';
                this.seo.description = '{{ old('seo-description') }}';
            }
        })
    </script>
@endpush
