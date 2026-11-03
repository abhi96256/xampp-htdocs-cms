@extends('adminlte::page')

@section('title', 'Add Role')

@section('content_header')
    <h1>Add Role</h1>
@stop

@section('content')
   
        @if ($errors->any())
    <div class="border border-danger text-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    <form method="post" action="{{ route('roles.create') }}">  

			@csrf

			<div class="row">
				<div class="form-group col-sm-8">
					<label class="text-capitalize" for="name">Name</label>
					<input type="text" class="form-control text-capitalize" id="name" name="role" placeholder="Role Name"
					value="" required>
				</div>
				<div class="form-group col-sm-12">
					<label class="text-capitalize" for="details">Permissions</label>
					<div class="row">
					@foreach($permissions as $p)
						@php
							$parts = explode('_', $p->name);
							$action = array_pop($parts);
							$module = implode('_', $parts);
							
							$moduleLabel = ucwords(str_replace('_', ' ', $module));
							$actionLabel = strtolower($action);
							$actionDescription = '';

							switch($actionLabel) {
								case 'access': $actionDescription = "Can view the list and access the module."; break;
								case 'create': $actionDescription = "Can create new records."; break;
								case 'edit': $actionDescription = "Can edit existing records."; break;
								case 'show': $actionDescription = "Can view detailed information of records."; break;
								case 'delete': $actionDescription = "Can delete records."; break;
								case 'import': $actionDescription = "Can import data."; break;
								case 'export': $actionDescription = "Can export data."; break;
								default: $actionDescription = "Can $actionLabel."; break;
							}

							$customDescriptions = [
								'leads_import' => 'Allows importing leads from a file.',
								'leads_export' => 'Allows exporting leads to a file.',
								'user_management_access' => 'Grants access to manage user accounts.',
								'2_factor_authentication' => 'Enable Two-Factor Authentication.',
							];
							$description = $customDescriptions[$p->name] ?? "{$actionDescription} ({$moduleLabel})";
							$title = ucwords(str_replace('_', ' ', $p->name));
						@endphp
						<div class="col-md-4 col-sm-6 mb-3">
							<div class="form-check" >
							  <input class="form-check-input" type="checkbox" name="permissions[]" value="{{$p->id}}" id="permission_{{$p->id}}" 
							 >
							  <label class="form-check-label" for="permission_{{$p->id}}">
							 	<strong>{{ $title }}</strong><br>
								<small class="text-muted">{{ $description }}</small>
							  </label>
							</div>
						</div>
					@endforeach
					</div>
				</div>
				</div>
			</div>

			<div class="form-group">
				<input type="submit" class="btn btn-success" value="Create">
				<a class="btn btn-danger ml-3" href="{{ route('roles.index') }}">Cancel</a>
			</div>
           
        </div>
		</form>

        {{-- Pagination links --}}
       

    </div>
@stop

@section('css')
@stop

@section('js')
    <script src="{{ asset('js/table_utils.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/user_lost.js') }}"></script>
@stop
