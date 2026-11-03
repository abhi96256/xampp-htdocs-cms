@extends('adminlte::page')

@section('title', 'Leads')

@section('content_header')
    <h1>Leads</h1>
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
        <form method="post" action="" enctype="multipart/form-data">
            @csrf
			 <div class="form-group col-sm-3">
                    <label class="text-capitalize" for="password">Admin Verification code</label>
                    <input type="text" class="form-control" id="password" name="admin_code"
                    placeholder="Admin code" required>
                </div>
                <div class="form-group col-sm-3">
                    <label class="text-capitalize" for="password_confirmation">Verification code</label>
                    <input type="text" class="form-control" id="password_confirmation" name="code"
                    placeholder="Verification code" required>
                </div>
		 <div class="form-group">
				<input type="hidden" name="file_type" value="{{$file_type}}">
                <input type="submit" class="btn btn-success" value="Submit">
                <a class="btn btn-danger ml-3" href="{{ route('dashboard.index') }}">Cancel</a>
            </div>
        </form>

    </div>
@stop

@section('css')
@stop

@section('js')
@stop
