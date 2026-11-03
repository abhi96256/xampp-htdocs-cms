@extends('adminlte::page')

@section('title', 'Edit Lead')

@section('content_header')
    <h1>Edit Lead</h1>
@stop

@section('content')
    <form method="post" action="{{ route('leads.update', ['id' => $lead->id]) }}">
        @include('leads.form')
    </form>
@stop

@section('css')
@stop

@section('js')
@stop
