@extends('adminlte::page')

@section('title', 'Add New Lead')

@section('content_header')
    <h1>Add New Lead</h1>
@stop

@section('content')
    <form method="post" action="{{ route('leads.store') }}">
        @include('leads.form')
    </form>
@stop

@section('css')
@stop

@section('js')
@stop
