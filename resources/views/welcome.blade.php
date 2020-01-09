@extends('layouts.app')

@section('content')
        <div id="carouselExampleControls" class="carousel slide" style="margin-top: -3rem;" data-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="//via.placeholder.com/2560x800" class="d-block w-100" alt="...">
                </div>
                <div class="carousel-item">
                    <img src="//via.placeholder.com/2560x800" class="d-block w-100" alt="...">
                </div>
                <div class="carousel-item">
                    <img src="//via.placeholder.com/2560x800" class="d-block w-100" alt="...">
                </div>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
        <div class="container">
        </div>
@stop
