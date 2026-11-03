@extends('adminlte::page')

@section('title', 'Verify User Creation')

@section('content_header')
    <h1>Verify User Creation (OTP)</h1>
    @if ($errors->any())
        <div class="border border-danger text-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if (Session::has('success'))
        <div class="border border-danger text-danger">
            <ul>
                <li>{{ Session::get('success') }}</li>
            </ul>
        </div>
    @endif
@stop

@section('content')
    <div class="card px-3 py-2">
        <form method="post" action="{{ route('useraccounts.store') }}" enctype="multipart/form-data">
            @csrf

                <div class="form-group col-sm-3">
                    <label class="text-capitalize" for="code">Verification code</label>
                    <input type="text" class="form-control" id="code" name="code"
                    placeholder="Verification code" required>
                </div>
		    <div class="form-group">
                <input type="submit" class="btn btn-success" value="Submit">
                <a class="btn btn-danger ml-3" href="{{ route('useraccounts.create') }}">Cancel</a>
            </div>
        </form>

    </div>
@stop

@section('css')
@stop

@section('js')
@stop
