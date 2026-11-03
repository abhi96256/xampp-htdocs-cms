@if ($errors->any())
    <div class="border border-danger text-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@php
    if (!function_exists('getAncestorsAll')) {
        function getAncestorsAll($user_id, &$memo = []) {
            if(isset($memo[$user_id])) return $memo[$user_id];
            $tms = \App\Models\TeamMember::with('parent')->where('user_id', $user_id)->get();
            if($tms->isEmpty()) {
                $memo[$user_id] = [];
                return [];
            }
            $ancestors = [];
            foreach($tms as $tm) {
                if($tm->parent) {
                    $ancestors[] = $tm->parent->name;
                    $ancestors = array_merge($ancestors, getAncestorsAll($tm->parent->id, $memo));
                }
            }
            $memo[$user_id] = array_values(array_unique($ancestors));
            return $memo[$user_id];
        }
    }
    $memo = [];
@endphp

@csrf

<h4>Personal Details</h4>
<div class="row">
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="name">Name</label>
        @if(isset($enquiry))
            <input type="text" class="form-control text-capitalize" id="name" name="name" placeholder="Full Name"
            value="{{ $enquiry->name }}" required>
        @else
            <input type="text" class="form-control text-capitalize" id="name" name="name" placeholder="Full Name"
            value="@if(isset($client)){{ $client->name }}@else{{ old('name') }}@endif" required>
        @endif
    </div>

    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="email">Email</label>
        @if(isset($enquiry))
            <input type="email" class="form-control" id="email" name="email" placeholder="user@example.com"
            value="{{ $enquiry->email }}">
        @else
            <input type="email" class="form-control" id="email" name="email" placeholder="user@example.com"
            value="@if(isset($client)){{ $client->email }}@else{{ old('email') }}@endif">
        @endif
    </div>

    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="contact_no">Contact Number</label>
        
        @php
            $enquiry_numbers = \App\Models\Enquiry::pluck('contact_no')->unique()->filter();
        @endphp
        <datalist id="enquiry_numbers_list">
            @foreach($enquiry_numbers as $num)
                <option value="{{ $num }}"></option>
            @endforeach
        </datalist>

        @if(isset($enquiry))
            <input type="tel" class="form-control" id="contact_no" name="contact_no" placeholder="9101234567" maxlength="10" pattern="[0-9]{10}" list="enquiry_numbers_list"
            value="{{ $enquiry->contact_no }}" required autocomplete="off">
        @else
            <input type="tel" class="form-control" id="contact_no" name="contact_no" placeholder="9101234567" maxlength="10" pattern="[0-9]{10}" list="enquiry_numbers_list"
            value="@if(isset($client)){{ $client->contact_no }}@else{{ old('contact_no') }}@endif" required autocomplete="off">
        @endif
    </div>
</div>

<div class="row">
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="subject">Description</label>
        @if(isset($enquiry))
            <textarea class="form-control" id="subject" name="subject" placeholder="Description"
            >{{ $enquiry->subject }}</textarea>
        @else
            <textarea class="form-control" id="subject" name="subject" placeholder="Description"
            >@if(isset($client)){{ $client->subject }}@else{{ old('subject') }}@endif</textarea>
        @endif
    </div>

    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="rating">Rating</label>
        <select class="form-control" id="rating" name="rating" required>
            <option value="1">&starf;</option>
            <option value="2">&starf;&starf;</option>
            <option value="3">&starf;&starf;&starf;</option>
            <option value="4">&starf;&starf;&starf;&starf;</option>
            <option value="5">&starf;&starf;&starf;&starf;&starf;</option>
        </select>
    </div>

    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="closed_by">Closed By</label>
        <select class="form-control" id="closed_by_select" name="closed_by" required>
            <option value="">Select User</option>
            @foreach ($users as $user)
                <option value="{{ $user->id }}" 
                    @if(isset($client) && $client->closed_by == $user->id) selected 
                    @elseif(!isset($client) && auth()->user() && auth()->user()->id == $user->id) selected @endif>
                    {{ $user->name }}
                </option>
            @endforeach
            @if(isset($client) && $client->closed_by_name)
                <option value="{{ $client->closed_by_name }}" selected>{{ $client->closed_by_name }}</option>
            @endif
        </select>
    </div>
</div>

@php
    // Build a JS-ready map: userId => [ancestor names]
    $userAncestorsMap = [];
    $memo2 = [];
    foreach($users as $u) {
        $userAncestorsMap[$u->id] = getAncestorsAll($u->id, $memo2);
    }
@endphp

<div class="row">
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="closed_by_team1">Closed By Team 1</label>
        <select class="form-control" id="closed_by_team1" name="closed_by_team1">
            <option value="">Select Team 1</option>
            @foreach ($users as $user)
                <option value="{{ $user->name }}" 
                    @if(isset($client) && $client->closed_by_team1 == $user->name) selected @endif>
                    {{ $user->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="closed_by_team2">Closed By Team 2</label>
        <select class="form-control" id="closed_by_team2" name="closed_by_team2">
            <option value="">Select Team 2</option>
            @foreach ($users as $user)
                <option value="{{ $user->name }}" 
                    @if(isset($client) && $client->closed_by_team2 == $user->name) selected @endif>
                    {{ $user->name }}
                </option>
            @endforeach
        </select>
    </div>
</div>
<div class="row">
    <div class="form-group form-check ml-3">
        @if(isset($enquiry))
            <input type='checkbox' class="form-check-input" name='is_active' checked disabled />
        @else
            @if(isset($client))
                <input type='checkbox' class="form-check-input" name='is_active' @if(isset($client->is_active))@if($client->is_active)checked @else @endif @endif />
            @else
                <input type='checkbox' class="form-check-input" name='is_active' checked />
            @endif
        @endif
        <label class="form-check-label" for="is_active">Is Active?</label>
    </div>
</div>
<br><br>

<h4>Booking Details</h4>
<div class="row">
	<div class="form-group col-sm-3">
	 <label class="text-capitalize" for="booking_date_picker">Booking Date</label>
			<input type="text" class="form-control" id="booking_date_picker" name="booking_date" placeholder="Select Booking Date"
			@if(isset($client) && $client->booking_date)
				value="{{ \Carbon\Carbon::parse($client->booking_date)->format('d-M-Y') }}"
			@elseif(isset($client))
				value="<?php echo date('d-M-Y', strtotime($client->created_at)); ?>"
			@else
				value="{{ date('d-M-Y') }}"
			@endif
			readonly>
	</div>
</div>
<div class="row">
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="project">Project</label>
        <select class="form-control js-example-basic-single" id="project" name="project" required>
            @if(isset($enquiry))
                @foreach ($projects as $project)
                    <option value="{{ $project->id }}" @if(isset($enquiry->project->id))@if($project->id == $enquiry->project->id) selected @endif @endif>{{ $project->name }}</option>
                @endforeach
            @else
                @foreach ($projects as $project)
                    <option value="{{ $project->id }}" @if(isset($client->project->id)) @if($project->id == $client->project->id) selected @endif @endif>{{ $project->name }}</option>
                @endforeach
            @endif
        </select>
    </div>

    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="configuration">Configuration</label>
        <select class="form-control js-example-basic-single" id="configuration" name="configuration" required>
            @if(isset($enquiry))
                @foreach ($configurations as $configuration)
                    <option value="{{ $configuration->id }}" @if(isset($enquiry->configuration->id))@if($configuration->id == $enquiry->configuration->id) selected @endif @endif>{{ $configuration->name }}</option>
                @endforeach
            @else
                @foreach ($configurations as $configuration)
                    <option value="{{ $configuration->id }}" @if(isset($client->configuration->id)) @if($configuration->id == $client->configuration->id) selected @endif @endif>{{ $configuration->name }}</option>
                @endforeach
            @endif
        </select>
    </div>

    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="carpet_area">Carpet Area (Sq. Ft.)/Super Area</label>
        <input type="number" step="0.01" class="form-control" id="carpet_area" name="carpet_area" placeholder="Carpet area"
        @if(isset($client))value="{{ $client->carpet_area }}"@endif >
    </div>

    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="booking_amount">Booking Amount ({{ App\Lancer\Utilities::CURRENCY_SYMBOL }})</label>
        <input type="number" step="0.01" class="form-control" id="booking_amount" name="booking_amount" placeholder="Booking amount"
        @if(isset($client))value="{{ $client->booking_amount }}"@endif required>
    </div>
</div>
<div class="row">
	<div class="form-group col-sm-3">
        <label class="text-capitalize" for="unit_no">Unit No</label>
        <input type="text" class="form-control" id="unit_no" name="unit_no" placeholder="e.g. 101A, B-204"
        @if(isset($client))value="{{ $client->unit_no }}"@endif >
    </div>
	<div class="form-group col-sm-3">
        <label class="text-capitalize" for="floor_no">Floor No</label>
        <input type="text" class="form-control" id="floor_no" name="floor_no" placeholder="e.g. 5, GF, FF"
        @if(isset($client))value="{{ $client->floor_no }}"@endif >
    </div>
	<div class="form-group col-sm-3">
        <label class="text-capitalize" for="tower_no">Tower No</label>
        <input type="text" class="form-control" id="tower_no" name="tower_no" placeholder="e.g. A, B, T1"
        @if(isset($client))value="{{ $client->tower_no }}"@endif >
    </div>
	<div class="form-group col-sm-3">
        <label class="text-capitalize" for="actual_amount">Actual Amount ({{ App\Lancer\Utilities::CURRENCY_SYMBOL }})</label>
        <input type="number" step="0.01" class="form-control" id="actual_amount" name="actual_amount" placeholder="Actual Amount"
        @if(isset($client))value="{{ $client->actual_amount }}"@endif required>
    </div>
</div>
<div class="row">
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="agreement_value">Agreement Value ({{ App\Lancer\Utilities::CURRENCY_SYMBOL }})</label>
        <input type="number" step="0.01" class="form-control" id="agreement_value" name="agreement_value" placeholder="Agreement value"
        @if(isset($client))value="{{ $client->agreement_value }}"@endif required>
    </div>
	<div class="form-group col-sm-3">
        <label class="text-capitalize" for="agreement_value">Payment Plan </label>
        <input type="text"  class="form-control" id="payment_plan" name="payment_plan" placeholder="Payment Plan"
        @if(isset($client))value="{{ $client->payment_plan }}"@endif required>
    </div>

    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="payment_mode">Payment Mode</label>
        <select class="form-control js-example-basic-single" id="payment_mode" onchange="otherSelect(event)"  name="payment_mode" required>
            @foreach ($payment_modes as $payment_mode)
            <option @if(isset($client))@if($client->payment_mode->id === $payment_mode->id) selected @endif @endif
                value="{{ $payment_mode->id }}">{{ $payment_mode->name }}</option>
            @endforeach
        </select>
		<div id="otherBox" style="visibility: hidden;">
		 <input name="cheque_no" type="text" placeholder="Enter Cheque no" class="form-control" /> 
		</div>
    </div>
	<div class="form-group col-sm-3">
        <label class="text-capitalize" for="attachment">Payment Proof</label>
        <input type="file" class="form-control" id="attachment" name="attachment">
    </div>
</div>

<div class="row">
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="pass_on_to_customer">Pass On To Customer</label>
        <input type="number" step="0.01" class="form-control" id="pass_on_to_customer" name="pass_on_to_customer" placeholder="Amount" value="{{ isset($client) ? ($client->pass_on_to_customer ?? '') : old('pass_on_to_customer', '') }}">
    </div>
</div>

@if( auth()->user()->hasRole('Admin') == true || auth()->user()->can('incentive_access'))
<br><br>

<h4>Company Incentive Details</h4>
<div class="row">

    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="brokerage_percent">Incentive (%)</label>
        <input type="number" step="0.01" class="form-control" id="brokerage_percent" name="incentive_percent"
        placeholder="Incentive percent" value="@if(isset($incentive->incentive_percentage)){{$incentive->incentive_percentage}}@endif">
    </div>

    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="brokerage_amount">Incentive Amount ({{ App\Lancer\Utilities::CURRENCY_SYMBOL }})</label>
        <input type="number" step="0.01" class="form-control" id="brokerage_amount" name="incentive_amount"
        placeholder="Incentive Amount" value="@if(isset($incentive->incentive_amount)){{$incentive->incentive_amount}}@endif" >
    </div>
	
	<div class="form-group col-sm-3">
        <label class="text-capitalize" for="brokerage_remark">Remark</label>
        <input type="text" class="form-control" id="brokerage_remark" name="incentive_remark" placeholder="Remarks" value="@if(isset($incentive->remarks)){{$incentive->remarks}}@endif">
    </div>

    <div class="form-group col-sm-3">
        <label class="text-capitalize">Net Incentive</label>
        <div class="form-control" style="background-color: #e9ecef" readonly>
            @php
                $dbIncentiveAmt = isset($incentive->incentive_amount) ? floatval($incentive->incentive_amount) : 0;
                $dbPassOn = isset($client->pass_on_to_customer) ? floatval($client->pass_on_to_customer) : 0;
                $dbNetIncentive = $dbIncentiveAmt - $dbPassOn;
            @endphp
            <span id="net_incentive_display">{{ number_format($dbNetIncentive, 2) }}</span>
        </div>
    </div>
</div>



<h4>Employee Incentive Details</h4>
<div class="row">
		<div class=" mt-3" data-x-wrapper="emp_incentive">
		@if(isset($emp_incentive) && count($emp_incentive) > 0)
		@foreach($emp_incentive as $inc)
			<div class="d-flex align-items-end gap-2 my-2 flex-wrap" data-x-group>
				<div class="form-group mb-0" style="flex:1; min-width:150px;">
					<label class="text-capitalize">Incentive For</label>
					<select class="form-control incentive_user_select" name="emp_incentive[{{ $loop->index }}][user_id]">
						@foreach ($users as $user)
							<option value="{{ $user->id }}" data-salary="{{ $user->salary }}" @if($user->id == $inc->user_id) selected @endif>{{ $user->name }}</option>
						@endforeach
					</select>
				</div>
				<div class="form-group mb-0" style="flex:1; min-width:120px;">
					<label class="text-capitalize" for="incentive_employee_percent">Incentive (%)</label>
					<input type="number" step="0.01" class="form-control incentive_employee_percent" name="emp_incentive[{{ $loop->index }}][incentive_employee_percent]"
					placeholder="Incentive percent" value="{{$inc->incentive_percentage}}">
				</div>
				<div class="form-group mb-0" style="flex:1; min-width:100px;">
					<label class="text-capitalize">TDS (%)</label>
					<input type="number" step="0.01" class="form-control incentive_tds_percent" name="emp_incentive[{{ $loop->index }}][tds_percent]"
					placeholder="TDS percent" value="{{$inc->tds_percent ?? ''}}">
				</div>
				<div class="form-group mb-0" style="flex:1; min-width:120px;">
					<label class="text-capitalize">TDS Amount</label>
					<input type="number" step="0.01" class="form-control incentive_tds_amount" name="emp_incentive[{{ $loop->index }}][tds_amount]"
					placeholder="TDS Amount" value="{{$inc->tds_amount ?? ''}}">
				</div>
				<div class="form-group mb-0" style="flex:1; min-width:150px;">
					<label class="text-capitalize">Incentive Amount ({{ App\Lancer\Utilities::CURRENCY_SYMBOL }})</label>
					<input type="number" step="0.01" class="form-control incentive_employee_amount" name="emp_incentive[{{ $loop->index }}][incentive_employee_amount]"
					placeholder="Incentive Amount" value="{{$inc->incentive_amount}}">
				</div>
				<div class="form-group mb-0 d-flex gap-1">
					<button type="button" class="btn btn-danger remove_dup" onclick="removeRow(this)">-</button>
					<button type="button" class="btn btn-primary add_dup" onclick="addRow(this)">+</button>
				</div>
			</div>
			@endforeach
			@else
				<div class="d-flex align-items-end gap-2 my-2 flex-wrap" data-x-group>
					<div class="form-group mb-0" style="flex:1; min-width:150px;">
						<label class="text-capitalize">Incentive For</label>
						<select class="form-control incentive_user_select" name="emp_incentive[0][user_id]">
							@foreach ($users as $user)
								<option value="{{ $user->id }}" data-salary="{{ $user->salary }}">{{ $user->name }}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group mb-0" style="flex:1; min-width:120px;">
						<label class="text-capitalize" for="brokerage_percent">Incentive (%)</label>
						<input type="number" step="0.01" class="form-control incentive_employee_percent" name="emp_incentive[0][incentive_employee_percent]"
						placeholder="Incentive percent" value="">
					</div>
					<div class="form-group mb-0" style="flex:1; min-width:100px;">
						<label class="text-capitalize">TDS (%)</label>
						<input type="number" step="0.01" class="form-control incentive_tds_percent" name="emp_incentive[0][tds_percent]"
						placeholder="TDS percent" value="">
					</div>
					<div class="form-group mb-0" style="flex:1; min-width:120px;">
						<label class="text-capitalize">TDS Amount</label>
						<input type="number" step="0.01" class="form-control incentive_tds_amount" name="emp_incentive[0][tds_amount]"
						placeholder="TDS Amount" value="">
					</div>
					<div class="form-group mb-0" style="flex:1; min-width:150px;">
						<label class="text-capitalize">Incentive Amount ({{ App\Lancer\Utilities::CURRENCY_SYMBOL }})</label>
						<input type="number" step="0.01" class="form-control incentive_employee_amount" name="emp_incentive[0][incentive_employee_amount]"
						placeholder="Incentive Amount" value="">
					</div>
					<div class="form-group mb-0 d-flex gap-1">
						<button type="button" class="btn btn-danger remove_dup" onclick="removeRow(this)">-</button>
						<button type="button" class="btn btn-primary add_dup" onclick="addRow(this)">+</button>
					</div>
				</div>
		@endif
		</div>
	
</div>

@endif

<div class="form-group">
<input type="hidden" name="lead_id" value="@if(isset($enquiry->id)){{$enquiry->id}}@endif">
    <button type="submit" class="btn btn-success">
        @if(isset($enquiry))
            Save
        @else
            @if(isset($client))
                Update
            @else
                Create
            @endif
        @endif
    </button>
    <a class="btn btn-danger ml-3" href="{{ route('clients.index') }}">Cancel</a>
</div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
flatpickr("#booking_date_picker", {
    dateFormat: "d-M-Y",
    allowInput: false,
    defaultDate: document.getElementById('booking_date_picker').value || new Date(),
});
</script>
<script>
function addRow(btn) {
    var $ = jQuery;
    var wrapperSelector = '[data-x-wrapper]';
    var groupSelector = '[data-x-group]';
    
    var $btn = $(btn);
    var $wrapper = $btn.closest(wrapperSelector);
    var arrayName = $wrapper.attr('data-x-wrapper');
    var $lastGroup = $btn.closest(groupSelector);
    var $newGroup = $lastGroup.clone();

    // Clean up Select2 artifacts from the clone before inserting
    $newGroup.find('.select2-container').remove();
    $newGroup.find('.js-example-basic-single, .incentive-select2')
             .removeClass('select2-hidden-accessible')
             .removeAttr('data-select2-id aria-hidden tabindex style');
    $newGroup.find('option').removeAttr('data-select2-id');

    // Clear values and original names to ensure fresh indices
    $newGroup.find('input, select, textarea').val('');
    $newGroup.find('input, select, textarea').removeAttr('data-original-name');
    $newGroup.find('input:checkbox, input:radio').prop('checked', false);

    // Insert
    $newGroup.insertAfter($lastGroup);

    // Re-initialize Select2 exactly as it was on the new row
    $newGroup.find('.js-example-basic-single').select2({ width: 'element' });
    $newGroup.find('.incentive-select2').select2({ width: 'element', tags: true });

    // Update names
    updateIndices($wrapper, arrayName);
}

function removeRow(btn) {
    var $ = jQuery;
    var wrapperSelector = '[data-x-wrapper]';
    var groupSelector = '[data-x-group]';
    
    var $btn = $(btn);
    var $wrapper = $btn.closest(wrapperSelector);
    var arrayName = $wrapper.attr('data-x-wrapper');
    var $groups = $wrapper.find(groupSelector);

    if ($groups.length > 1) {
        $btn.closest(groupSelector).remove();
        updateIndices($wrapper, arrayName);
    } else {
        alert("At least one row is required!");
    }
}

function updateIndices($wrapper, arrayName) {
    var $ = jQuery;
    var groupSelector = '[data-x-group]';
    
    $wrapper.find(groupSelector).each(function(index) {
        $(this).find('input, select, textarea').each(function() {
            var name = $(this).attr('data-original-name');
            if (!name) {
                name = $(this).attr('name');
                if (name) {
                    name = name.replace(/.*\[([^\]]+)\]$/, '$1');
                    $(this).attr('data-original-name', name);
                }
            }
            if (name) {
                $(this).attr('name', arrayName + '[' + index + '][' + name + ']');
            }
        });
    });
}

$(document).ready(function() {
    // Phone validation & Datalist strict enforcement
    var validNumbers = [];
    $('#enquiry_numbers_list option').each(function() {
        validNumbers.push($(this).val());
    });

    var $contactInput = $("input[name='contact_no']");
    
    $contactInput.on('input', function (e) {
        $(this).val($(this).val().replace(/[^0-9]/g, ''));
    });

    // Check on change/blur to ensure it exists in leads
    $contactInput.on('change', function() {
        var val = $(this).val();
        if (val && validNumbers.length > 0 && validNumbers.indexOf(val) === -1) {
            alert("This number does not exist in the Leads list! You can only add clients for existing leads.");
            $(this).val('');
        }
    });

    // Initial indices
    $('[data-x-wrapper]').each(function() {
        updateIndices($(this), $(this).attr('data-x-wrapper'));
    });

    // Removed auto-populate team dropdowns as requested

    // Net Incentive Calculation
    function calculateNetIncentive() {
        let incentiveAmt = parseFloat($('#brokerage_amount').val()) || 0;
        let passOnToCust = parseFloat($('#pass_on_to_customer').val()) || 0;
        let net = incentiveAmt - passOnToCust;
        $('#net_incentive_display').text(net.toFixed(2));
    }
    
    $('#brokerage_amount, #pass_on_to_customer').on('input', calculateNetIncentive);
    
    // Calculate on load
    calculateNetIncentive();
});

function otherSelect(event) {
    var other = document.getElementById("otherBox");
    if (jQuery("#payment_mode option:selected").text() == "Cheque") {
        other.style.visibility = "visible";
    } else {
        other.style.visibility = "hidden";
    }
}
</script>
