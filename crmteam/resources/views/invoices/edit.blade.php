@extends('adminlte::page')

@section('title', 'Edit Invoice')

@section('content_header')
    <h1>Edit Invoice</h1>
@stop

@section('content')

    <form method="post" enctype="multipart/form-data" action="{{ route('invoices.update', ['id' => $invoice->id]) }}">
        @include('invoices.form')
    </form>

@stop

@section('css')
@stop

@section('js')
    <script src="{{ asset('js/s2.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/brokerage_calculator.js') }}?v={{ time() }}"></script>
@stop
