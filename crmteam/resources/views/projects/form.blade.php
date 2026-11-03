@if ($errors->any())
    <div class="border border-danger text-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@csrf
<div class="row">
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="name">Date</label>
        <input type="text" class="form-control text-capitalize" id="name" name="date" placeholder="Full Name"
        value="<?php if(isset($project)){$originalDate = $project->created_at;
			echo $newDate = date("d-M-Y", strtotime($originalDate));} else{echo Date('d-M-Y');}?>">
    </div>
</div>
<div class="row">
    <div class="form-group col-sm-4">
        <label class="text-capitalize" for="name">Name</label>
        <input type="text" class="form-control text-capitalize" id="name" name="name" placeholder="Project name"
        value="@if(isset($project)){{ $project->name }}@else{{ old('name') }}@endif" required>
    </div>
	<div class="form-group col-sm-4">
        <label class="text-capitalize" for="name">Location</label>
        <input type="text" class="form-control text-capitalize" id="name" name="location" placeholder="Enter Location"
        value="@if(isset($project)){{ $project->location }}@else{{ old('location') }}@endif" required>
    </div>
    <div class="form-group col-sm-4">
        <label class="text-capitalize" for="details">Project Brochure</label>
        <input type="file" class="form-control" id="brochure" name="brochure" >
    </div>
</div>
<div class="row">
   
    <div class="form-group col-sm-8">
        <label class="text-capitalize" for="details">Address</label>
        <textarea class="form-control" id="details" name="address" placeholder="Address"
        required>@if(isset($project)){{ $project->address }}@else{{ old('address') }}@endif</textarea>
    </div>
</div>

<div class="form-group">
    <input type="submit" class="btn btn-success" value="@if(isset($project)) Update @else Create @endif">
    <a class="btn btn-danger ml-3" href="{{ route('projects.index') }}">Cancel</a>
</div>
