@extends('adminlte::page')

@section('title', 'Add Office Payment')

@section('content_header')
    <h1>Add Office Payment</h1>
@stop

@section('content')
    <div class="card px-4 py-4 mt-3">
        <form method="post" action="{{ route('office_payment.store') }}">
            @include('office_payments.form')
        </form>
    </div>
@stop
