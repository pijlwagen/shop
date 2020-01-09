@extends('layouts.admin')

@section('content')
    <div class="container-fluid px-5">
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="row">
                @csrf
                <div class="col-xl-9 col-lg-10 mb-3">
                    <div class="card shadow-sm">
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
                                <div class="tab-pane fade show active" id="general" role="tabpanel">

                                </div>
                                <div class="tab-pane fade" id="seo" role="tabpanel" aria-labelledby="seo-tab">
                                    <div class="form-group row">
                                        <div class="col-md-7">
                                            <label for="seo-title">Meta title</label>
                                            <input type="text" name="seo-title" id="seo-title"
                                                   class="form-control @error('seo-title') is-invalid @enderror"
                                                   value="{{ old('seo-title', $settings->where('key', 'seo-title')->first()->value ?? config('app.name')) }}">
                                            @error('seo-title')
                                            <div class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="col-md-5">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-2">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h2 class="text-center">Actions</h2>
                        </div>
                        <div class="card-body">
                            <button class="btn btn-primary">Save</button>
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
                search: '',
            },
            created: function () {
                this.base = $('#search-replace').html()
            },
            watch: {
                search: function () {
                    if (!this.search || this.search.length < 2) return $('#search-replace').html(this.base);
                    $.get('/dashboard/products/search?search=' + this.search)
                        .done(function (res) {
                            $('#search-replace').html(res);
                        });
                }
            },
            methods: {
                hide: function (id) {
                    $.post('/dashboard/products/' + id + '/hide', {
                        _token: '{{ csrf_token() }}'
                    }).done(function (res) {
                        if (res.hidden) {
                            $('#hide-category-' + id).html('<i class="fa fa-check text-success"></i>')
                        } else {
                            $('#hide-category-' + id).html('<i class="fa fa-times text-danger"></i>')
                        }
                    });
                }
            }
        });
    </script>
@endpush
