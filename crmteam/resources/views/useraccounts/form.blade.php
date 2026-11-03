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
        <label class="text-capitalize" for="name">Name<span class="red">*</span></label>
        <input type="text" class="form-control text-capitalize" id="name" name="name" placeholder="Full Name"
        value="@if(isset($user)){{ $user->name }}@else{{ old('name') }}@endif" required>
    </div>
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="email">Email<span class="red">*</span></label>
        <input type="email" class="form-control" id="email" name="email" placeholder="username@example.com"
        value="@if(isset($user)){{ $user->email }}@else{{ old('email') }}@endif" required>
    </div>
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="photo">Photo</label>
        <input type="file" class="form-control" id="photo" name="photo" accept="image/png, image/jpeg">
    </div>
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="photo">Current Photo</label>
        @if (isset($user) && $user->photo_url)
            <img src="{{ $user->photo_url }}" alt='profile photo'
            class="img-thumbnail" style="height: 42px; width: 42px; object-fit: cover;" />
        @else
            <span class="text-muted d-block mt-2">
                No photo provided.
            </span>
        @endif
    </div>
</div>

<div class="row">
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="role">Role<span class="red">*</span></label>
        <select class="form-control js-example-basic-single" id="role" name="role" required>
            @foreach ($roles as $role)
                <option value="{{ $role->id }}" @if(isset($user)) @if($user->roles->first()->id == $role->id) selected @endif @endif>{{ $role->name }}</option>
            @endforeach
        </select>
    </div>
	@if (!isset($user))
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="password">Password<span class="red">*</span></label>
        <input type="password" class="form-control" id="password" value="@if (isset($user->password)){{$user->password}}@else{{''}}@endif" name="password"
        placeholder="Password" required>
    </div>
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="password_confirmation">Confirm Password<span class="red">*</span></label>
        <input type="password" class="form-control" value="@if (isset($user->password)){{$user->password}}@else{{''}}@endif" id="password_confirmation" name="password_confirmation"
        placeholder="Confirm password" required>
    </div>
	@endif
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="phone">Phone<span class="red">*</span></label>
        <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter Phone number" maxlength="10" pattern="{0-9}" 
        value="@if(isset($user)){{ $user->phone }}@else{{ '' }}@endif" required> 
    </div>
    <div class="form-group col-sm-6">
        <label class="text-capitalize" for="address">Address</label>
        <textarea name="address" class="form-control">@if(isset($user)){{ $user->address }}@else{{ '' }}@endif</textarea>
    </div>
</div>
<div class="row">
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="aadhar_front">Aadhar Card Front</label>
        <input type="file" class="form-control" id="aadhar_front" name="aadhar_front" accept=".pdf, image/*">
    </div>
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="aadhar_front">Aadhar Front Preview</label>
        @if (isset($user) && $user->aadhar_front) 
            <a href="{{ $user->aadhar_front }}" target="_blank" class="btn btn-sm btn-info"><i class="fas fa-file"></i> View File</a>
        @else
            <span class="text-muted d-block mt-2">No file provided.</span>
        @endif
    </div>
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="aadhar_back">Aadhar Card Back</label>
        <input type="file" class="form-control" id="aadhar_back" name="aadhar_back" accept=".pdf, image/*">
    </div>
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="aadhar_back">Aadhar Back Preview</label>
        @if (isset($user) && $user->aadhar_back) 
            <a href="{{ $user->aadhar_back }}" target="_blank" class="btn btn-sm btn-info"><i class="fas fa-file"></i> View File</a>
        @else
            <span class="text-muted d-block mt-2">No file provided.</span>
        @endif
    </div>
</div>
<div class="row">
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="pan_card_front">Pan Card Front</label>
        <input type="file" class="form-control" id="pan_card_front" name="pan_card_front" accept=".pdf, image/*">
    </div>
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="pan_card_front">Pan Front Preview</label>
        @if (isset($user) && $user->pan_card_front)
            <a href="{{ $user->pan_card_front }}" target="_blank" class="btn btn-sm btn-info"><i class="fas fa-file"></i> View File</a>
        @else
            <span class="text-muted d-block mt-2">No file provided.</span>
        @endif
    </div>
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="pan_card_back">Pan Card Back</label>
        <input type="file" class="form-control" id="pan_card_back" name="pan_card_back" accept=".pdf, image/*">
    </div>
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="pan_card_back">Pan Back Preview</label>
        @if (isset($user) && $user->pan_card_back)
            <a href="{{ $user->pan_card_back }}" target="_blank" class="btn btn-sm btn-info"><i class="fas fa-file"></i> View File</a>
        @else
            <span class="text-muted d-block mt-2">No file provided.</span>
        @endif
    </div>
</div>
<div class="row">
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="resume">Resume Attach</label>
        <input type="file" class="form-control" id="resume" name="resume" accept=".pdf, image/*">
    </div>
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="resume">Resume Preview</label>
        @if (isset($user) && $user->resume)
            <a href="{{ $user->resume }}" target="_blank" class="btn btn-sm btn-info"><i class="fas fa-file"></i> View File</a>
        @else
            <span class="text-muted d-block mt-2">No file provided.</span>
        @endif
    </div>
</div>
<div class="row">
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="police_verification">Police Verification</label>
        <input type="file" class="form-control" id="police_verification" name="police_verification">
    </div>
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="police_verification_no">Verification/File No.</label>
        <input type="text" class="form-control" id="police_verification_no" name="police_verification_no" placeholder="Enter File No."
        value="@if(isset($user)){{ $user->police_verification_no }}@else{{ old('police_verification_no') }}@endif">
    </div>
    <div class="form-group col-sm-6">
        <label class="text-capitalize" for="police_verification_preview">Police Verification File</label>
        @if (isset($user) && $user->police_verification)
            <br>
            <a href="{{ $user->police_verification }}" target="_blank" class="btn btn-sm btn-info"><i class="fas fa-file"></i> View Uploaded File</a>
        @else
            <span class="text-muted d-block mt-2">
                No file provided.
            </span>
        @endif
    </div>
</div>
<div class="row">
    <div class="form-group col-sm-6">
        <label class="text-capitalize" for="user_verification">User Verification Document</label>
        <input type="file" class="form-control" id="user_verification" name="user_verification">
    </div>
    <div class="form-group col-sm-6">
        <label class="text-capitalize" for="user_verification_preview">User Verification File View</label>
        @if (isset($user) && $user->user_verification)
            <br>
            <a href="{{ $user->user_verification }}" target="_blank" class="btn btn-sm btn-info"><i class="fas fa-file"></i> View Uploaded File</a>
        @else
            <span class="text-muted d-block mt-2">
                No file provided.
            </span>
        @endif
    </div>
</div>
<div class="row">
	<div class="form-group col-sm-3">
        <label class="text-capitalize" for="role">Export Leads<span class="red">*</span></label>
        <select class="form-control js-example-basic-single" id="role" name="export" required>                
                <option value="0" @if(isset($user) && $user->export == 0)
				<?php echo "selected";?> @endif> No</option>
				<option value="1" @if(isset($user) && $user->export == 1)
				<?php echo "selected";?> @endif> Yes</option>
        </select>
    </div>
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="role">Status<span class="red">*</span></label>
        <select class="form-control js-example-basic-single" id="role" name="status" required>                
                <option value="0" @if(isset($user) && $user->status == 0)
				<?php echo "selected";?> @endif> InActive</option>
				<option value="1" @if(isset($user) && $user->status == 1)
				<?php echo "selected";?> @endif> Active</option>
        </select>
    </div>
	<div class="form-group col-sm-3">
        <label class="text-capitalize" for="phone">Location<span class="red">*</span></label>
        <input type="text" class="form-control" id="location" name="location" placeholder="Enter location"  
        value="@if(isset($user->location)){{ $user->location }}@else{{ '' }}@endif" required> 
    </div>
	<div class="form-group col-sm-3">
        <label class="text-capitalize">Pay Type<span class="red">*</span></label>
        <select class="form-control" id="pay_type" name="pay_type" required>
            <option value="salary" @if(isset($user) && empty($user->incentive_percentage)) selected @endif>Salary</option>
            <option value="commission" @if(isset($user) && !empty($user->incentive_percentage)) selected @endif>Commission</option>
        </select>
    </div>
	<div class="form-group col-sm-3" id="salary_div">
        <label class="text-capitalize">Salary Amount<span class="red">*</span></label>
        <input type="text" class="form-control" id="salary" name="salary" placeholder="Enter Salary amount"  
        value="@if(isset($user->salary)){{ $user->salary }}@else{{ '' }}@endif"> 
    </div>
	<div class="form-group col-sm-3" id="commission_div" style="display:none;">
        <label class="text-capitalize">Commission Percentage (%)<span class="red">*</span></label>
        <input type="text" class="form-control" id="incentive_percentage" name="incentive_percentage" placeholder="Enter Commission %"  
        value="@if(isset($user->incentive_percentage)){{ $user->incentive_percentage }}@else{{ '' }}@endif"> 
    </div>
   
</div>

{{-- References Section --}}
<hr>
<h6 class="text-secondary mb-2"><i class="fas fa-user-friends"></i> References</h6>
<div class="row">
    <div class="form-group col-sm-3">
        <label class="text-capitalize">Reference 1 - Name</label>
        <input type="text" class="form-control" name="reference_name_1" placeholder="Enter Reference Name"
        value="@if(isset($user)){{ $user->reference_name_1 }}@else{{ old('reference_name_1') }}@endif">
    </div>
    <div class="form-group col-sm-3">
        <label class="text-capitalize">Reference 1 - Phone No.</label>
        <input type="text" class="form-control ref-phone" name="reference_phone_1" placeholder="Enter Phone No." maxlength="10"
        value="@if(isset($user)){{ $user->reference_phone_1 }}@else{{ old('reference_phone_1') }}@endif">
    </div>
    <div class="form-group col-sm-3">
        <label class="text-capitalize">Reference 2 - Name</label>
        <input type="text" class="form-control" name="reference_name_2" placeholder="Enter Reference Name"
        value="@if(isset($user)){{ $user->reference_name_2 }}@else{{ old('reference_name_2') }}@endif">
    </div>
    <div class="form-group col-sm-3">
        <label class="text-capitalize">Reference 2 - Phone No.</label>
        <input type="text" class="form-control ref-phone" name="reference_phone_2" placeholder="Enter Phone No." maxlength="10"
        value="@if(isset($user)){{ $user->reference_phone_2 }}@else{{ old('reference_phone_2') }}@endif">
    </div>
</div>



<div class="form-group">
    <input type="submit" class="btn btn-success" value="@if(isset($user)) Update @else Create @endif">
    <a class="btn btn-danger ml-3" href="{{ route('useraccounts.index') }}">Cancel</a>
</div>
<style>
.red{
	color:#ff0000;
}
</style>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    jQuery(function(){
        jQuery("input[name='phone'], .ref-phone").on('input', function (e) {
            jQuery(this).val($(this).val().replace(/[^0-9]/g, ''));
        });

        // Pay Type Toggle
        function togglePayType() {
            var type = jQuery('#pay_type').val();
            if (type === 'salary') {
                jQuery('#salary_div').show();
                jQuery('#commission_div').hide();
                // Optional: clear commission when switching to salary
                if(!'{{isset($user) ? 1 : 0}}') jQuery('#incentive_percentage').val('');
            } else {
                jQuery('#salary_div').hide();
                jQuery('#commission_div').show();
                // Optional: clear salary when switching to commission
                if(!'{{isset($user) ? 1 : 0}}') jQuery('#salary').val('');
            }
        }
        jQuery('#pay_type').on('change', togglePayType);
        togglePayType();
    });
</script>
