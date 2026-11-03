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
        value="<?php if(isset($enquiry)){$originalDate = $enquiry->created_at;
			echo $newDate = date("d-M-Y", strtotime($originalDate));} else{echo Date('d-M-Y');}?>">
    </div>
</div>
<div class="row">
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="name">Name<span class="red">*</span></label>
        <input type="text" class="form-control text-capitalize" id="name" name="name" placeholder="Full Name"
        value="@if(isset($enquiry)){{ $enquiry->name }}@else{{ old('name') }}@endif" required>
    </div>
   
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="email">Email</label>
        <input type="email" class="form-control" id="email" name="email" placeholder="user@example.com"
        value="@if(isset($enquiry)){{ $enquiry->email }}@else{{ old('email') }}@endif">
    </div>
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="contact_no">Contact Number<span class="red">*</span></label>
        <input type="tel" class="form-control" id="contact_no" name="contact_no" placeholder="+910123456789" maxlength="10" pattern="{0-9}" 
        value="@if(isset($enquiry)){{ $enquiry->contact_no }}@else{{ old('contact_no') }}@endif" required>
    </div> 
	<div class="form-group col-sm-3">
        <label class="text-capitalize" for="contact_no">Alternate Contact Number</label>
        <input type="tel" class="form-control" id="contact_no" name="a_contact_no" placeholder="+910123456789" maxlength="10" pattern="{0-9}" 
        value="@if(isset($enquiry)){{ $enquiry->a_contact_no }}@else{{ old('a_contact_no') }}@endif">
    </div>
</div>

<div class="row">
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="project">Project</label>
        <select class="form-control js-example-basic-single" id="project" name="project">
		<option value="" 
		@if(!isset($enquiry->project->id)) 
					<?php echo "selected";?>
					@endif 
					>Select</option>
            @foreach ($projects as $project)
                <option value="{{ $project->id }}" @if(isset($enquiry->project->id)) @if($project->id == $enquiry->project->id) selected @endif @endif>{{ $project->name }}</option>
            @endforeach
	
        </select>
    </div>
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="project_1">Project 1</label>
        <select class="form-control js-example-basic-single" id="project_1" name="project_1">
            <option value="" @if(!isset($enquiry->project_id_1)) selected @endif>Select</option>
            @foreach ($projects as $project)
                <option value="{{ $project->id }}" @if(isset($enquiry->project_id_1) && $project->id == $enquiry->project_id_1) selected @endif>{{ $project->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="project_2">Project 2</label>
        <select class="form-control js-example-basic-single" id="project_2" name="project_2">
            <option value="" @if(!isset($enquiry->project_id_2)) selected @endif>Select</option>
            @foreach ($projects as $project)
                <option value="{{ $project->id }}" @if(isset($enquiry->project_id_2) && $project->id == $enquiry->project_id_2) selected @endif>{{ $project->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="project_3">Project 3</label>
        <select class="form-control js-example-basic-single" id="project_3" name="project_3">
            <option value="" @if(!isset($enquiry->project_id_3)) selected @endif>Select</option>
            @foreach ($projects as $project)
                <option value="{{ $project->id }}" @if(isset($enquiry->project_id_3) && $project->id == $enquiry->project_id_3) selected @endif>{{ $project->name }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="row">
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="project_4">Project 4</label>
        <select class="form-control js-example-basic-single" id="project_4" name="project_4">
            <option value="" @if(!isset($enquiry->project_id_4)) selected @endif>Select</option>
            @foreach ($projects as $project)
                <option value="{{ $project->id }}" @if(isset($enquiry->project_id_4) && $project->id == $enquiry->project_id_4) selected @endif>{{ $project->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="configuration">Configuration</label>
        <input type="text" class="form-control" id="configuration" name="other_config" placeholder="Enter Configuration" value="@if(isset($enquiry)){{ $enquiry->other_config }}@else{{ old('other_config') }}@endif">
    </div>
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="budget_range">Budget Range</label>
        <input type="tel" class="form-control" id="budget_range" name="budget_range" placeholder="Enter Budget" 
        value="@if(isset($enquiry)){{ $enquiry->budget_range }}@else{{ old('budget_range') }}@endif">
    </div>
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="enquiry_status">Status<span class="red">*</span></label>
        <select class="form-control js-example-basic-single" id="enquiry_status" name="enquiry_status" required>
            @foreach ($enquiry_statuses as $status)
                <option value="{{ $status->id }}" @if(isset($enquiry)) @if($status->id == $enquiry->enquiry_status->id) selected @endif @endif>{{ $status->status }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="row">
   <div class="form-group col-sm-3">
        <label class="text-capitalize" for="address">Address</label>
        <textarea class="form-control" id="address" name="address" placeholder="Enter Address">@if(isset($enquiry)){{ $enquiry->address }}@else{{ old('address') }}@endif</textarea>
    </div>

    @if(auth()->user()->hasRole('Admin'))
        <div class="form-group col-sm-3">
            <label class="text-capitalize" for="assigned_to">Assign To</label>
            <select class="form-control js-example-basic-single" id="assigned_to" name="assigned_to" >
				<option value="" @if(isset($enquiry->assignedTo) == '') 
					<?php echo "selected";?>
					@endif >Select</option>
			
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" @if(isset($enquiry->assignedTo) && $user->id == $enquiry->assignedTo->id) 
					<?php echo "selected";?> 
					@endif >{{ $user->name }}</option>
                @endforeach
				
            </select>
        </div>
  
    @endif
	
	 <div class="form-group col-sm-3">
        <label class="text-capitalize" for="subject">Remark</label>
        <textarea class="form-control" id="subject" name="subject" placeholder="Remark"
        >@if(isset($enquiry)){{ $enquiry->subject }}@else{{ old('subject') }}@endif</textarea>
    </div>

    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="reference_by">Reference By</label>
        <input type="text" class="form-control" id="reference_by" name="reference_by" placeholder="Reference Name"
        value="@if(isset($enquiry)){{ $enquiry->reference_by }}@else{{ old('reference_by') }}@endif">
    </div>
</div>



<div class="form-group">
    <input type="submit" class="btn btn-success" value="@if(isset($enquiry)) Update @else Create @endif">
    <a class="btn btn-danger ml-3" href="{{ Url('/').'/enquiries/index' }}">Cancel</a>
</div>

<style>
.red{
		color:#ff0000;
	}
</style>
<script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="{{url('/').'/js/jquery.replicate.js'}}"></script>

<script>
		
jQuery(function(){
  jQuery("input[name='contact_no']").on('input', function (e) {
    jQuery(this).val($(this).val().replace(/[^0-9]/g, ''));
  });
});
function otherSelect(event) {
	
 var other = document.getElementById("otherBox");
 if ($( "#configuration  option:selected" ).text() == "Other") {
 other.style.visibility = "visible";
 }
 else {
 other.style.visibility = "hidden";
 }
 }
</script>
