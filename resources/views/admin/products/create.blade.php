@extends('layouts.admin')

@section('content')
    <div class="container-fluid px-md-5">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
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
                                <li class="nav-item">
                                    <a class="nav-link" id="discount-tab" data-toggle="tab" href="#discount" role="tab"
                                       aria-controls="discount" aria-selected="false">Discount</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="image-tab" data-toggle="tab" href="#image" role="tab"
                                       aria-controls="image" aria-selected="false">Images</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="custom-tab" data-toggle="tab" href="#custom" role="tab"
                                       aria-controls="custom" aria-selected="false">Custom Options</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="general" role="tabpanel"
                                     aria-labelledby="general-tab">
                                    <div class="form-group row">
                                        <div class="col-lg-7 mb-3">
                                            <label for="name"><i class="fa fa-tag"></i> Name</label>
                                            <input type="text" name="name" id="name" placeholder="My Product"
                                                   class="form-control @error('name') is-invalid @enderror"
                                                   value="{{ old('name') }}" v-model="name">
                                            @error('name')
                                            <div class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="col-lg-5 mb-3">
                                            <label for="price"><i class="fa fa-dollar-sign"></i> Price</label>
                                            <input type="number" name="price" id="price" step="0.01"
                                                   class="form-control @error('price') is-invalid @enderror"
                                                   value="{{ old('price', '0.00') }}">
                                            @error('price')
                                            <div class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-lg-7 mb-3">
                                            <label for="slug"><i class="fa fa-search"></i> Slug</label>
                                            <div class="input-group mb-2 mr-sm-2">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">{{ config('app.url') }}/product/</div>
                                                </div>
                                                <input type="text" name="slug" id="slug"
                                                       placeholder="my-example-product"
                                                       class="form-control @error('slug') is-invalid @enderror"
                                                       value="{{ old('slug') }}">
                                                @error('slug')
                                                <div class="invalid-feedback" role="alert">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                            <small class="text-muted">This will be the product in the URL, only
                                                alphanumeric
                                                characters
                                                and dashes are allowed</small></div>
                                        <div class="col-lg-5 mb-3">
                                            <label for="stock"><i class="fa fa-boxes"></i> Stock</label>
                                            <input type="number" name="stock" id="stock"
                                                   class="form-control @error('stock') is-invalid @enderror"
                                                   value="{{ old('stock', '0') }}">
                                            @error('stock')
                                            <div class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-12">
                                            <label for="description"><i class="fa fa-pen-fancy"></i> Description</label>
                                            <textarea name="description" id="description" cols="30" rows="5"
                                                      v-model="description"
                                                      class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                                            @error('description')
                                            <div class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="categories"><i class="fa fa-list-ul"></i> Categories</label>
                                        <select name="categories[]" id="categories"
                                                class="form-control @error('categories') is-invalid @enderror" multiple>
                                            @foreach($categories as $category)
                                                <option
                                                    value="{{ $category->id }}" {{ in_array($category->id, old('categories', [])) }}>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('categories')
                                        <div class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="seo" role="tabpanel" aria-labelledby="seo-tab">
                                    <div class="form-group row">
                                        <div class="col-lg-12">
                                            <label for="seo-description">Meta Description</label>
                                            <textarea type="text" name="seo-description" id="seo-description"
                                                      v-bind:readonly="seo.descriptionSameAsDescription" rows="5"
                                                      class="form-control @error('seo-description') is-invalid @enderror"
                                                      v-model="seo.description">
                                {{ old('seo-description') }}
                            </textarea>
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
                                <div class="tab-pane fade" id="image" role="tabpanel" aria-labelledby="image-tab">
                                    <div class="form-group">
                                        <label for="images"><i class="fa fa-images"></i> Images</label>
                                        <input type="file" name="images[]" id="images"
                                               class="form-control-file @error('images') is-invalid @enderror" multiple>
                                        <small class="text-muted">Only .png, .jpg, and .jpeg are accepted.</small>
                                        @error('images')
                                        <div class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="custom" role="tabpanel" aria-labelledby="custom-tab">
                                    <div class="form-group">
                                        <p>
                                            If you want custom options for you product, for example: colors, sizes etc.
                                            You can add them here. Click on add new option, give the field a title and
                                            add values for that option.
                                        </p>
                                    </div>
                                    <div class="form-group">
                                        <button type="button" class="btn btn-primary w-100" v-on:click="add()">Add new
                                            option
                                        </button>
                                    </div>
                                    <div class="card mb-3" v-for="(option, index) in custom">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-lg-9">
                                                    <h2><% custom[index].title || 'Unnamed option' %></h2>
                                                    <div class="form-group row">
                                                        <div class="col-md-5">
                                                            <label v-bind:for="'type-' + index">Option type</label>
                                                            <select v-bind:name="'option[' + index + '][type]'"
                                                                    v-bind:class="{'is-invalid': !custom[index].type}"
                                                                    v-bind:id="'type-' + index" class="form-control"
                                                                    v-model="custom[index].type">
                                                                <option v-for="(type, index) in types"
                                                                        :value="type.type"><%
                                                                    type.name %>
                                                                </option>
                                                            </select>
                                                            <div class="invalid-feedback" v-if="!custom[index].type"
                                                                 role="alert">
                                                                This field is required
                                                            </div>
                                                        </div>
                                                        <div class="col-md-5">
                                                            <label v-bind:for="'title-' + index">Option title
                                                                text</label>
                                                            <input v-bind:name="'option[' + index + '][title]'"
                                                                   v-bind:class="{'is-invalid': !custom[index].title}"
                                                                   v-bind:id="'title-' + index" class="form-control"
                                                                   v-model="custom[index].title">
                                                            <div class="invalid-feedback" v-if="!custom[index].title"
                                                                 role="alert">
                                                                This field is required
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2 d-flex">
                                                            <button class="btn btn-danger my-auto w-100" type="button"
                                                                    v-on:click="remove(index)">Delete
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <button class="btn btn-primary float-right" type="button"
                                                            v-if="custom[index].values.length < 1"
                                                            v-on:click="addValue(index)">Add value
                                                    </button>
                                                    <button class="btn btn-primary float-right" type="button" v-else
                                                            v-on:click="addValue(index)">Add another value
                                                    </button>
                                                    <div class="form-group">
                                                        <h3>Option values</h3>
                                                    </div>
                                                    <div class="form-group row"
                                                         v-for="(value, child) in custom[index].values">
                                                        <div class="col-md-7">
                                                            <label v-bind:for="'title-value-' + index">Value
                                                                text</label>
                                                            <input
                                                                v-bind:name="'option[' + index + '][values]['+child+'][value]'"
                                                                v-bind:class="{'is-invalid': !custom[index].values[child].value}"
                                                                v-bind:id="'title-value-' + index" class="form-control"
                                                                v-model="custom[index].values[child].value">
                                                            <div class="invalid-feedback"
                                                                 v-if="!custom[index].values[child].value"
                                                                 role="alert">
                                                                This field is required
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label v-bind:for="'title-price-' + index">Value
                                                                price <small class="text-muted">(optional)</small></label>
                                                            <input
                                                                v-bind:name="'option[' + index + '][values]['+child+'][increment]'"
                                                                v-bind:id="'title-price-' + index" class="form-control"
                                                                v-model="custom[index].values[child].increment">
                                                        </div>
                                                        <div class="col-md-2 d-flex">
                                                            <button class="btn btn-danger my-auto w-100" type="button"
                                                                    v-on:click="removeValue(index, child)">Delete
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="card">
                                                        <div class="card-header">
                                                            <h4>Preview</h4>
                                                        </div>
                                                        <div class="card-body">
                                                            <label><% custom[index].title || 'Unnamed option' %></label>
                                                            <select class="form-control">
                                                                <option v-for="(value, child) in custom[index].values">
                                                                    <% value.value %> <% value.increment ? '&euro;' + value.increment : '' %>
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="discount" role="tabpanel" aria-labelledby="discount-tab">
                                    <div class="form-group">
                                        <button class="btn btn-primary w-100" type="button" v-on:click="addDiscount()">
                                            Add discount
                                        </button>
                                    </div>
                                    <div class="card mb-3" v-for="(discount, index) in discounts">
                                        <div class="card-body">
                                            <div class="form-group row">
                                                <div class="col-md-6">
                                                    <label v-bind:for="'discount-type-' + index">Type</label>
                                                    <select v-bind:name="'discount['+index+'][type]'"
                                                            v-bind:id="'discount-type-' + index" class="form-control"
                                                            v-bind:class="{'is-invalid': !discounts[index].type}"
                                                            v-model="discounts[index].type">
                                                        <option value="" readonly="">Pick one...</option>
                                                        <option v-for="(type) in discountType" :value="type.value"><%
                                                            type.title %>
                                                        </option>
                                                    </select>
                                                    <div class="invalid-feedback" role="alert"
                                                         v-if="!discounts[index].type">
                                                        Please choose one of the options
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label v-bind:for="'discount-amount-' + index">Amount</label>
                                                    <input type="number" v-bind:name="'discount['+index+'][amount]'"
                                                           v-bind:id="'discount-amount-' + index" class="form-control"
                                                           v-if="discounts[index].type != 'free'"
                                                           v-bind:disabled="discounts[index].type == 'free'" step="0.01"
                                                           v-model="discounts[index].amount"
                                                           v-bind:class="{'is-invalid': !discounts[index].amount} || discounts[index].amount < 0.01">
                                                    <input type="number" v-bind:name="'discount['+index+'][amount]'"
                                                           v-bind:id="'discount-amount-' + index" class="form-control"
                                                           v-else
                                                           disabled>
                                                    <div class="invalid-feedback" role="alert"
                                                         v-if="!discounts[index].amount || discounts[index].amount < 0.01">
                                                        Amount must be greater then 0.01
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-md-6">
                                                    <label v-bind:for="'discount-start-date-' + index">Start
                                                        date</label>
                                                    <input type="date" v-bind:name="'discount['+index+'][start][date]'"
                                                           v-bind:id="'discount-start-date-' + index"
                                                           class="form-control"
                                                           v-model="discounts[index].start.date"
                                                           v-bind:class="{'is-invalid': !discounts[index].start.date}">
                                                    <div class="invalid-feedback" v-if="!discounts[index].start.date"
                                                         role="alert">
                                                        This field is required
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label v-bind:for="'discount-start-time-' + index">Start
                                                        time</label>
                                                    <input type="time" v-bind:name="'discount['+index+'][start][time]'"
                                                           v-bind:id="'discount-start-time-' + index"
                                                           class="form-control"
                                                           v-model="discounts[index].start.time"
                                                           v-bind:class="{'is-invalid': !discounts[index].start.time}">
                                                    <div class="invalid-feedback" v-if="!discounts[index].start.time"
                                                         role="alert">
                                                        This field is required
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-md-6">
                                                    <label v-bind:for="'discount-end-date-' + index">End date</label>
                                                    <input type="date" v-bind:name="'discount['+index+'][end][date]'"
                                                           v-bind:id="'discount-end-date-' + index" class="form-control"
                                                           v-model="discounts[index].end.date"
                                                           v-bind:class="{'is-invalid': !discounts[index].end.date}">
                                                    <div class="invalid-feedback" v-if="!discounts[index].end.date"
                                                         role="alert">
                                                        This field is required
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label v-bind:for="'discount-end-time-' + index">End time</label>
                                                    <input type="time" v-bind:name="'discount['+index+'][end][time]'"
                                                           v-bind:id="'discount-end-time-' + index" class="form-control"
                                                           v-model="discounts[index].end.time"
                                                           v-bind:class="{'is-invalid': !discounts[index].end.time}">
                                                    <div class="invalid-feedback" v-if="!discounts[index].end.date"
                                                         role="alert">
                                                        This field is required
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <button class="btn btn-danger w-100" type="button" v-on:click="removeDiscount(index)">Delete</button>
                                            </div>
                                        </div>
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
                name: "",
                description: "",
                seo: {
                    title: '',
                    titleSameAsName: true,
                    description: '',
                    descriptionSameAsDescription: true,
                },
                custom: [],
                types: [
                    {
                        name: 'Select box [Dropdown]',
                        type: 'select',
                        selected: true,
                    }
                ],
                discounts: [],
                discountType: [
                    {
                        title: 'Fixed - A specific amount',
                        value: 'fixed',
                    },
                    {
                        title: 'Percentage - A percentage of the price',
                        value: 'percentage'
                    },
                    {
                        title: 'Free',
                        value: 'free'
                    }
                ]
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
            methods: {
                add: function () {
                    this.custom.push({
                        type: this.types[0].type,
                        title: '',
                        values: []
                    });
                },
                remove: function (index) {
                    this.custom.splice(index, 1);
                },
                addValue: function (parent) {
                    this.custom[parent].values.push({value: '', increment: ''})
                },
                removeValue: function (parent, index) {
                    this.custom[parent].values.splice(index, 1);
                },
                addDiscount: function () {
                    this.discounts.push({
                        type: '',
                        amount: 0.01,
                        start: {
                            date: '',
                            time: ''
                        },
                        end: {
                            date: '',
                            time: ''
                        }
                    })
                },
                removeDiscount: function (index) {
                    this.discounts.splice(index, 1);
                }
            },
            created: function () {
                this.custom = JSON.parse('{{ json_encode(old('option', [])) }}'.replace(/&quot;/g, '"'));
                this.name = "{{ old('name') }}";
                this.description = "{{ old('description') }}";
                this.seo.title = "{{ old('seo-title') }}";
                this.seo.description = "{{ old('seo-description') }}";
                this.discounts = JSON.parse('{{ json_encode(old('discount', [])) }}'.replace(/&quot;/g, '"'));
            },
            delimiters: ['<%', '%>']
        });
    </script>
@endpush
