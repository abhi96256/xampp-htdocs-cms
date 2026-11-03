@extends('adminlte::page')

@section('title', 'Import Leads')

@section('content_header')
    <h1>Import Leads</h1>
@stop

@section('content')

    <form method="post" action="{{ route('enquiries.store_import') }}" enctype= "multipart/form-data">
	  @csrf

       <div class="row">
			<div class="form-group col-sm-9">
				<label class="text-capitalize" for="name">Upload File</label>
				<p>For uploading leads please following the format of sample Leads file. <a href="{{url('/').'/storage/galeryImages/leads.csv'}}" download="leads.csv">Click here </a> to download the sample file</p>
				<input type="file" class="form-control text-capitalize" id="name" name="import_file" required>
			</div>
		</div>
		<div class="form-group">
			<input type="submit" class="btn btn-success" value="Import">
		</div>
    </form>

@stop

@section('css')
@stop

@section('js')
    <script src="{{ asset('js/s2.js') }}"></script>
@stop
