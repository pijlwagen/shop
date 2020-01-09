@extends('layouts.admin')

@section('content')
    <div class="container-fluid px-5">
        <div class="row">
            <div class="col-xl-8 col-lg-7">
                <div class="card shadow-sm mb-3">
                    <div class="card-body">
                        <input type="text" name="search" id="search" class="form-control" v-model="search" placeholder="Search...">
                    </div>
                </div>
                <div id="search-replace">
                    @include('snippets.category-table', ['categories' => $categories])
                </div>
            </div>
            <div class="col-xl-4 col-lg-5">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h1>Overview</h1>
                    </div>
                    <div class="card-body">

                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@push('js')
    <script>
        var app = new Vue({
            el: '#app',
            data: {
                search: '',
            },
            created: function() {
                this.base = $('#search-replace').html()
            },
            watch: {
                search: function () {
                    if (!this.search || this.search.length < 2) return $('#search-replace').html(this.base);
                    $.get('/dashboard/categories/search?search=' + this.search)
                        .done(function (res) {
                            $('#search-replace').html(res);
                        });
                }
            },
            methods: {
                hide: function (id) {
                    $.post('/dashboard/categories/' + id + '/hide', {
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
