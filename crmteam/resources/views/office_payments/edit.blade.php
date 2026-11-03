@extends('adminlte::page')

@section('title', 'Edit Office Payment')

@section('content_header')
    <h1>Edit Office Payment</h1>
@stop

@section('content')
    <div class="card px-4 py-4 mt-3">
        <form method="post" action="{{ route('office_payment.update', $office_payment->id) }}">
            @include('office_payments.form')
        </form>
    </div>
@stop
