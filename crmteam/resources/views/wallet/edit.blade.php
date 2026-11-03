@extends('adminlte::page')

@section('title', 'Edit wallet')

@section('content_header')
    <h1>Edit Wallet</h1>
@stop

@section('content')
    <form method="post" action="{{ route('wallet.update', ['id' => $due->id]) }}">
        @include('wallet.form')
    </form>
@stop

@section('css')
@stop

@section('js')
    <script src="{{ asset('js/s2.js') }}"></script>
@stop
