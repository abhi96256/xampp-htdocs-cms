@extends('adminlte::page')

@section('title', 'Access Denied')

@section('content_header')
@stop

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 60vh;">
    <div class="col-md-5">
        <div class="card card-outline card-danger shadow-sm border-0 rounded-lg">
            <div class="card-body text-center p-4">
                <div class="mb-3">
                    <i class="fas fa-lock text-danger" style="font-size: 50px; opacity: 0.8;"></i>
                </div>
                <h1 class="font-weight-bold text-dark mb-1" style="font-size: 2.5rem;">403</h1>
                <h4 class="text-danger mb-3">Access Denied!</h4>
                <p class="text-muted mb-4" style="font-size: 1rem;">
                    You don't have permission to view this page or perform this action.
                    <br>
                    Please contact your administrator if you believe this is a mistake.
                </p>
                
                <a href="{{ url()->previous() !== url()->current() ? url()->previous() : url('/') }}" class="btn btn-danger px-4 rounded-pill shadow-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Go Back
                </a>
                
                <a href="{{ url('/') }}" class="btn btn-outline-secondary px-4 rounded-pill shadow-sm ml-2">
                    <i class="fas fa-home mr-1"></i> Dashboard
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        transition: transform 0.2s ease;
    }
    .card:hover {
        transform: translateY(-3px);
    }
</style>
@stop
