@extends('adminlte::page')

@section('title', 'Add Attendance')

@section('content_header')
    <h1>Add Attendance</h1>
@stop

@section('content')
    <div class="card px-4 py-4 mt-3 col-md-8 mx-auto">
        <form method="post" action="{{ route('attendance.store') }}">
            @include('attendance.form')
        </form>
    </div>
@stop
