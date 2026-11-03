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
        <label class="text-capitalize" for="expense_category">Expense Category</label>
		<input type="text" class="form-control text-capitalize" id="expense_category" name="expense_category" placeholder="Expenses Category"
        value="@if(isset($expense)){{ $expense->expense_category }}@else{{ '' }}@endif" required>
    
    </div>
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="payment_mode">Payment Mode</label>
        <select class="form-control js-example-basic-single" id="payment_mode" name="payment_mode" onchange="otherSelect(event)" required>
            @foreach ($payment_modes as $payment_mode)
                <option value="{{ $payment_mode->id }}" @if(isset($expense)) @if($payment_mode->id == $expense->payment_mode->id) selected @endif @endif>
                    {{ $payment_mode->name }}
                </option>
            @endforeach
        </select>
		<div id="otherBox" style="visibility: hidden;">
		 <input name="cheque_no" type="text" placeholder="Enter Cheque no" class="form-control" /> 
		</div>
    </div>
	
	<!--div class="form-group col-sm-3 cheque_details" -->
       <div id="otherBox" style="visibility: hidden;">
		 <input name="cheque_no" type="text" placeholder="Enter Cheque no" class="form-control" /> 
		</div>
    <!--</div-->
</div>

<div class="row">
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="payee">Payee</label>
        <input type="text" class="form-control text-capitalize" id="payee" name="payee" placeholder="Payee name"
        value="@if(isset($expense)){{ $expense->payee }}@else{{ old('') }}@endif" required>
    </div>
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="amount_paid">Amount ({{ App\Lancer\Utilities::CURRENCY_SYMBOL }})</label>
        <input type="number" step="0.01" class="form-control text-capitalize" id="amount_paid" name="amount_paid"
        value="@if(isset($expense)){{ $expense->amount_paid }}@else{{ old('amount_paid') }}@endif" required>
    </div>
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="date_of_payment">Date Of Payment</label>
        <input type="date" class="form-control" id="date_of_payment" name="date_of_payment"
        value="@if(isset($expense)){{ $expense->date_of_payment->format('Y-m-d') }}@else{{ date('Y-m-d') }}@endif" required>
    </div>
	<div class="form-group col-sm-3">
        <label class="text-capitalize" for="bill_no">Bill no</label>
        <input type="text" class="form-control" id="bill_no" name="bill_no" placeholder="Bill no"
        value="@if(isset($expense)){{ $expense->bill_no }}@else{{ old('bill_no') }}@endif">
    </div>
	<div class="form-group col-sm-3">
        <label class="text-capitalize" for="bill_attachment">Bill Attachment<span class="red">*</span></label>
        <input type="file" class="form-control" id="bill_attachment" name="bill_attachment" @if(!isset($expense) || !$expense->bill_attachment) required @endif>
        @if(isset($expense) && $expense->bill_attachment)
            <div class="mt-2 text-sm">
                <a href="{{ asset('storage/galeryImages/' . $expense->bill_attachment) }}" target="_blank" class="text-primary font-weight-bold">
                    <i class="fas fa-paperclip"></i> View Attached File
                </a>
            </div>
        @endif
    </div>
	<div class="form-group col-sm-3">
        <label class="text-capitalize" for="address">Office Address</label>
        <input type="text" class="form-control" id="address" name="address" placeholder="Address"
        value="@if(isset($expense)){{ $expense->address }}@else{{ old('address') }}@endif">
    </div>
	<div class="form-group col-sm-3">
        <label class="text-capitalize" for="status">Status (By User)</label>
        <select class="form-control js-example-basic-single" id="status" name="status" style="width: 100%;">
            <option value="" @if(!isset($expense->status)) selected @endif>Select</option>
            <option value="Pending" @if(isset($expense) && $expense->status == 'Pending') selected @endif>Pending</option>
            <option value="Paid" @if(isset($expense) && $expense->status == 'Paid') selected @endif>Paid</option>
            <option value="Rejected" @if(isset($expense) && $expense->status == 'Rejected') selected @endif>Rejected</option>
        </select>
    </div>

	<div class="form-group col-sm-3">
        <label class="text-capitalize" for="paid_date_view">Paid Date (User)</label>
        <input type="text" class="form-control" id="paid_date_view" placeholder="N/A"
        value="@if(isset($expense) && $expense->paid_date){{ \Carbon\Carbon::parse($expense->paid_date)->format('d-M-Y h:i A') }}@endif" readonly>
    </div>
	<div class="form-group col-sm-3">
        <label class="text-capitalize" for="rejected_date_view">Rejected Date (User)</label>
        <input type="text" class="form-control" id="rejected_date_view" placeholder="N/A"
        value="@if(isset($expense) && $expense->rejected_date){{ \Carbon\Carbon::parse($expense->rejected_date)->format('d-M-Y h:i A') }}@endif" readonly>
    </div>

    {{-- ===== ADMIN SECTION ===== --}}
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="status_by_admin"><strong>Status (By Admin) <span class="red">*</span></strong></label>
        <select class="form-control js-example-basic-single" id="status_by_admin" name="status_by_admin" style="width: 100%;" required>
            <option value="" @if(!isset($expense->status_by_admin)) selected @endif>Select</option>
            <option value="Pending" @if(isset($expense) && $expense->status_by_admin == 'Pending') selected @endif>Pending</option>
            <option value="Paid" @if(isset($expense) && $expense->status_by_admin == 'Paid') selected @endif>Paid</option>
            <option value="Rejected" @if(isset($expense) && $expense->status_by_admin == 'Rejected') selected @endif>Rejected</option>
        </select>
    </div>
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="admin_paid_date_view"><strong>Paid Date (Admin)</strong></label>
        <input type="text" class="form-control" id="admin_paid_date_view" placeholder="N/A"
        value="@if(isset($expense) && $expense->admin_paid_date){{ \Carbon\Carbon::parse($expense->admin_paid_date)->format('d-M-Y h:i A') }}@endif" readonly>
    </div>
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="admin_rejected_date_view"><strong>Rejected Date (Admin)</strong></label>
        <input type="text" class="form-control" id="admin_rejected_date_view" placeholder="N/A"
        value="@if(isset($expense) && $expense->admin_rejected_date){{ \Carbon\Carbon::parse($expense->admin_rejected_date)->format('d-M-Y h:i A') }}@endif" readonly>
    </div>
</div>

<div class="form-group">
    <input type="submit" class="btn btn-success" value="@if(isset($expense)) Update @else Create @endif">
    <a class="btn btn-danger ml-3" href="{{ route('expenses.index') }}">Cancel</a>
</div>
<script>
function otherSelect(event) {
 var other = document.getElementById("otherBox");
 if ($( "#payment_mode  option:selected" ).text().trim() == "Cheque") {
 other.style.visibility = "visible";
 }
 else {
 other.style.visibility = "hidden";
 }
 }

function toggleAdminStatus() {
    var userStatus = $('#status').val().toLowerCase();
    var $adminSelect = $('#status_by_admin');

    if (userStatus === 'paid') {
        // User marked Paid → Admin not required, disable
        $adminSelect.prop('disabled', true);
        $adminSelect.closest('.form-group').find('label').first().html("<strong>Status (By Admin) — <span class='text-danger'>Disabled</span></strong>");
    } else {
        // User Pending/Rejected → Admin can act
        $adminSelect.prop('disabled', false);
        $adminSelect.closest('.form-group').find('label').first().html("<strong>Status (By Admin)</strong>");
    }
}

$(document).ready(function() {
    // Run on page load
    toggleAdminStatus();
    
    // Run on user status change (Select2 compatible)
    $('#status').on('change', function() {
        toggleAdminStatus();
    });
});
</script>
