@extends('adminlte::page')

@section('title', 'Import Invoices')

@section('content_header')
    <h1>Import Invoices</h1>
@stop

@section('content')

    <form method="post" action="{{ route('invoices.store_import') }}" enctype= "multipart/form-data">
	  @csrf

       <div class="row">
			<div class="form-group col-sm-9">
				<label class="text-capitalize" for="name">Upload File</label>
				<p>For uploading invoices, please verify your CSV format matches your exported format, or use the provided template button on the previous page if you generated one. Make sure it's CSV.</p>
				<input type="file" class="form-control text-capitalize" id="name" name="import_file" required accept=".csv">
			</div>
		</div>
		<div class="form-group">
			<input type="submit" class="btn btn-success" value="Import">
            <a href="{{ route('invoices.import_template') }}" class="btn btn-info ml-3"><i class="fas fa-download"></i> Download Template</a>
            <a class="btn btn-danger ml-3" href="{{ route('invoices.index') }}">Cancel</a>
		</div>
    </form>

@stop

@section('css')
@stop

@section('js')
@stop
