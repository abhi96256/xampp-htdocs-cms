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
        <label class="text-capitalize" for="name">Name</label>
        <input type="text" class="form-control text-capitalize" id="name" name="name" placeholder="Lead name"
        value="@if(isset($lead)){{ $lead->name }}@else{{ old('name') }}@endif" required>
    </div>
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="details">Date</label>
        <input class="form-control" id="details" name="date" placeholder="Date"
        required value="@if(isset($lead)){{ $lead->date }}@else{{ old('date') }}@endif">
    </div>
	<div class="form-group col-sm-3">
        <label class="text-capitalize" for="details">Mobile</label>
        <input class="form-control" id="details" name="mobile" placeholder="Mobile Number"
        required value="@if(isset($lead)){{ $lead->mobile }}@else{{ old('mobile') }}@endif">
    </div>
	<div class="form-group col-sm-3">
        <label class="text-capitalize" for="details">Email</label>
        <input class="form-control" id="details" name="email" placeholder="Email" type="email"
         value="@if(isset($lead)){{ $lead->email }}@else{{ old('email') }}@endif">
    </div>
	<div class="form-group col-sm-3">
        <label class="text-capitalize" for="details">Address</label>
        <input class="form-control" id="details" name="address" placeholder="Enter Address" type="text"
         value="@if(isset($lead)){{ $lead->address }}@else{{ old('address') }}@endif">
    </div>
	<div class="form-group col-sm-3">
        <label class="text-capitalize" for="details">Collection Mode</label>
        <input class="form-control" id="details" name="collection_mode" placeholder="Enter Collection Mode" type="text"
         value="@if(isset($lead)){{ $lead->collection_mode }}@else{{ old('collection_mode') }}@endif">
    </div>
	 @if(auth()->user()->hasRole('Admin'))
        <div class="form-group col-sm-3">
            <label class="text-capitalize" for="assigned_to">Assign To</label>
            <select class="form-control js-example-basic-single" id="assigned_to" name="assigned_to" required>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" @if(isset($enquiry)) @if($user->id == $enquiry->assignedTo->id) selected @endif @endif>{{ $user->name }} - {{ $user->no_of_enquiries_assigned }}</option>
                @endforeach
            </select>
        </div>
    @else
        <input type="hidden" name="assigned_to" value="{{ auth()->user()->id }}" />
    @endif
	 <div class="form-group col-sm-3">
        <label class="text-capitalize" for="enquiry_status">Status</label>
        <select class="form-control js-example-basic-single" id="enquiry_status" name="enquiry_status" required>
            @foreach ($enquiry_statuses as $status)
                <option value="{{ $status->id }}" @if(isset($enquiry)) @if($status->id == $enquiry->enquiry_status->id) selected @endif @endif>{{ $status->status }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="form-group">
    <input type="submit" class="btn btn-success" value="@if(isset($lead)) Update @else Create @endif">
    <a class="btn btn-danger ml-3" href="{{ route('leads.index') }}">Cancel</a>
</div>
