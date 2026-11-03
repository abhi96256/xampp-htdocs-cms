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

<h4>Invoice Details</h4>
<div class="row">
    <!-- Row 1 -->
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="bill_no">Bill Nos/Invoice Nos</label>
        <input type="text" class="form-control" id="bill_no" name="bill_no" placeholder="Enter Bill Nos"
        value="@if(isset($invoice)){{ $invoice->bill_no }}@else{{ old('bill_no') }}@endif" required>
    </div>
	
	<div class="form-group col-sm-3">
        <label class="text-capitalize" for="bill_date">Bill Nos/Invoice Nos Raised Date</label>
        <input type="text" class="form-control" id="bill_date" name="bill_date" placeholder="Enter Date"
        value="@if(isset($invoice)){{ $invoice->bill_date }}@else{{ date('Y/m/d') }}@endif" required>
    </div>

	<div class="form-group col-sm-3">
        <label class="text-capitalize" for="customer_name">Customer Name</label>
        <input type="text" class="form-control" id="customer_name" name="customer_name" placeholder="Enter Customer Name"
        value="@if(isset($invoice)){{ $invoice->customer_name }}@else{{ old('customer_name') }}@endif" required>
    </div>

	<div class="form-group col-sm-3">
        <label class="text-capitalize" for="company_developer">Company/Developer</label>
        <textarea class="form-control" id="company_developer" name="company_developer" placeholder="Enter Company/Developer"
        >@if(isset($invoice)){{ $invoice->company_developer }}@else{{ old('company_developer') }}@endif</textarea>
    </div>
    
    <!-- Row 2 -->
	<div class="form-group col-sm-3">
        <label class="text-capitalize" for="company_address">Addres of Company</label>
        <textarea class="form-control" id="company_address" name="company_address" placeholder="Enter Address"
        >@if(isset($invoice)){{ $invoice->company_address }}@else{{ old('company_address') }}@endif</textarea>
    </div>

    <!-- NEW FIELD -->
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="bill_attachment">Bill Attachment</label>
        <!-- Form MUST have enctype="multipart/form-data" -->
        <input type="file" class="form-control" id="bill_attachment" name="bill_attachment">
        @if(isset($invoice) && $invoice->bill_attachment)
            <br><a href="{{ url($invoice->bill_attachment) }}" target="_blank">View File</a>
        @endif
    </div>

	<div class="form-group col-sm-3">
        <label class="text-capitalize" for="bill_type">Type of bill</label>
        <input type="text" class="form-control" id="bill_type" name="bill_type" placeholder="Enter Bill Type"
        value="@if(isset($invoice)){{ $invoice->bill_type }}@else{{ old('bill_type') }}@endif" required>
    </div>

    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="property_amount">Amount (Rs.)</label>
        <input type="number" step="any" class="form-control" id="property_amount" name="property_amount" placeholder="Enter Amount"
        value="@if(isset($invoice)){{ $invoice->property_amount }}@else{{ old('property_amount') }}@endif" required>
    </div>

    <!-- Row 3 -->
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="gst_percentage">GST %</label>
        <input type="number" step="any" class="form-control" id="gst_percentage" name="gst_percentage" placeholder="e.g. 18"
        value="@if(isset($invoice)){{ $invoice->gst_percentage }}@else{{ old('gst_percentage') }}@endif">
    </div>

	<div class="form-group col-sm-3">
        <label class="text-capitalize" for="gst">GST</label>
        <input type="number" step="any" class="form-control" id="gst" name="gst" placeholder="Enter GST Amount"
        value="@if(isset($invoice)){{ $invoice->gst }}@else{{ old('gst') }}@endif" required>
    </div>

	<div class="form-group col-sm-3">
        <label class="text-capitalize" for="amount_for_developer">Total Amount Raised to Developer</label>
        <input type="number" step="any" class="form-control" id="amount_for_developer" name="amount_for_developer" placeholder="Enter Total Raised"
        value="@if(isset($invoice)){{ $invoice->amount_for_developer }}@else{{ old('amount_for_developer') }}@endif" required>
    </div>

	<div class="form-group col-sm-3">
		<label class="text-capitalize" for="gst_status">GST Status</label>
        <input type="text" class="form-control" id="gst_status" name="gst_status" placeholder="Enter GST Status"
        value="@if(isset($invoice)){{ $invoice->gst_status }}@else{{ old('gst_status') }}@endif" required>
	</div>

    <!-- Row 4 -->
	<div class="form-group col-sm-3">
		<label class="text-capitalize" for="bill_status">Status of Bill</label>
        <input type="text" class="form-control" id="bill_status" name="bill_status" placeholder="Enter Bill Status"
        value="@if(isset($invoice)){{ $invoice->bill_status }}@else{{ old('bill_status') }}@endif" required>
	</div>

    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="tds_percentage">TDS %</label>
        <input type="number" step="any" class="form-control" id="tds_percentage" name="tds_percentage" placeholder="e.g. 5"
        value="@if(isset($invoice)){{ $invoice->tds_percentage }}@else{{ old('tds_percentage') }}@endif">
    </div>

	<div class="form-group col-sm-3">
		<label class="text-capitalize" for="tds">TDS Amount</label>
        <input type="number" step="any" class="form-control" id="tds" name="tds" placeholder="Enter TDS Amount"
        value="@if(isset($invoice)){{ $invoice->tds }}@else{{ old('tds') }}@endif" required>
	</div>

	<div class="form-group col-sm-3">
		<label class="text-capitalize" for="amount_after_tds">Amount after TDS</label>
        <input type="number" step="any" class="form-control" id="amount_after_tds" name="amount_after_tds" placeholder="Amount after TDS"
        value="@if(isset($invoice)){{ $invoice->amount_after_tds }}@else{{ old('amount_after_tds') }}@endif" required>
	</div>
	
    <!-- Row 5 -->
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="customer_pass_on">Customer pass on</label>
        <input type="number" step="any" class="form-control" id="customer_pass_on" name="customer_pass_on" placeholder="Customer pass on"
        value="@if(isset($invoice)){{ $invoice->customer_pass_on }}@else{{ old('customer_pass_on') }}@endif">
    </div>

    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="customer_details">Customer Details</label>
        <textarea class="form-control" id="customer_details" name="customer_details" placeholder="Enter Customer Details"
        >@if(isset($invoice)){{ $invoice->customer_details }}@else{{ old('customer_details') }}@endif</textarea>
    </div>

	<div class="form-group col-sm-3">
		<label class="text-capitalize" for="after_customer_passon">After customer Pass on</label>
        <input type="number" step="any" class="form-control" id="after_customer_passon" name="after_customer_passon" placeholder="After customer Pass on"
        value="@if(isset($invoice)){{ $invoice->after_customer_passon }}@else{{ old('after_customer_passon') }}@endif" required>
	</div>

	<div class="form-group col-sm-3">
        <label class="text-capitalize" for="remarks">Remarks Any</label>
        <textarea class="form-control" id="remarks" name="remarks" placeholder="Enter Remarks"
        >@if(isset($invoice)){{ $invoice->remarks }}@else{{ old('remarks') }}@endif</textarea> 
    </div>
</div>
<h4>Commission Details</h4>
<h6>Commission 1</h6>
<hr/>
<div class="row">
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="commission_1_name">Name</label>
        <input type="text" class="form-control text-capitalize" id="commission_1_name" name="commission_1_name" placeholder="Enter Name"
        value="@if(isset($invoice)){{ $invoice->commission_1_name }}@else{{ old('commission_1_name') }}@endif" >
    </div>
    
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="commission_1_percentage">Percentage Commission</label>
        <input type="text" class="form-control text-capitalize" id="commission_1_percentage" name="commission_1_percentage" placeholder="Enter Percentage"
        value="@if(isset($invoice)){{ $invoice->commission_1_percentage }}@else{{ old('commission_1_percentage') }}@endif" >
    </div>
    
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="commission_1_amount">Amount</label>
        <input type="text" class="form-control text-capitalize" id="commission_1_amount" name="commission_1_amount" placeholder="Enter Amount"
        value="@if(isset($invoice)){{ $invoice->commission_1_amount }}@else{{ old('commission_1_amount') }}@endif" >
    </div>

    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="commission_1_tds">TDS %</label>
        <input type="text" class="form-control text-capitalize" id="commission_1_tds" name="commission_1_tds" placeholder="Enter TDS %"
        value="@if(isset($invoice)){{ $invoice->commission_1_tds }}@else{{ old('commission_1_tds') }}@endif" >
    </div>
    
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="commission_1_new">TDS Amount</label>
        <input type="text" class="form-control text-capitalize" id="commission_1_new" name="commission_1_new" placeholder="Enter TDS Amount"
        value="@if(isset($invoice)){{ $invoice->commission_1_new }}@else{{ old('commission_1_new') }}@endif" >
    </div>
    
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="commission_1_company_amount">Amount Paid</label>
        <input type="text" class="form-control text-capitalize" id="commission_1_company_amount" name="commission_1_company_amount" placeholder="Enter Amount Paid"
        value="@if(isset($invoice)){{ $invoice->commission_1_company_amount }}@else{{ old('commission_1_company_amount') }}@endif" >
    </div>
    
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="commission_1_cheque_no">Check Nos</label>
        <input type="text" class="form-control text-capitalize" id="commission_1_cheque_no" name="commission_1_cheque_no" placeholder="Enter Check No"
        value="@if(isset($invoice)){{ $invoice->commission_1_cheque_no }}@else{{ old('commission_1_cheque_no') }}@endif" >
    </div>
    
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="commission_1_date">Date</label>
        <input type="text" class="form-control text-capitalize" id="commission_1_date" name="commission_1_date" placeholder="Enter Date"
        value="@if(isset($invoice)){{ $invoice->commission_1_date }}@else{{ old('commission_1_date', date('d/m/Y')) }}@endif" >
    </div>  
</div>
<h6>Commission 2</h6>
<hr/>
<div class="row">
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="commission_2_name">Name</label>
        <input type="text" class="form-control text-capitalize" id="commission_2_name" name="commission_2_name" placeholder="Enter Name"
        value="@if(isset($invoice)){{ $invoice->commission_2_name }}@else{{ old('commission_2_name') }}@endif" >
    </div>
    
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="commission_2_percentage">Percentage Commission</label>
        <input type="text" class="form-control text-capitalize" id="commission_2_percentage" name="commission_2_percentage" placeholder="Enter Percentage"
        value="@if(isset($invoice)){{ $invoice->commission_2_percentage }}@else{{ old('commission_2_percentage') }}@endif" >
    </div>
    
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="commission_2_amount">Amount</label>
        <input type="text" class="form-control text-capitalize" id="commission_2_amount" name="commission_2_amount" placeholder="Enter Amount"
        value="@if(isset($invoice)){{ $invoice->commission_2_amount }}@else{{ old('commission_2_amount') }}@endif" >
    </div>

    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="commission_2_tds">TDS %</label>
        <input type="text" class="form-control text-capitalize" id="commission_2_tds" name="commission_2_tds" placeholder="Enter TDS %"
        value="@if(isset($invoice)){{ $invoice->commission_2_tds }}@else{{ old('commission_2_tds') }}@endif" >
    </div>
    
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="commission_2_new">TDS Amount</label>
        <input type="text" class="form-control text-capitalize" id="commission_2_new" name="commission_2_new" placeholder="Enter TDS Amount"
        value="@if(isset($invoice)){{ $invoice->commission_2_new }}@else{{ old('commission_2_new') }}@endif" >
    </div>
    
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="commission_2_company_amount">Amount Paid</label>
        <input type="text" class="form-control text-capitalize" id="commission_2_company_amount" name="commission_2_company_amount" placeholder="Enter Amount Paid"
        value="@if(isset($invoice)){{ $invoice->commission_2_company_amount }}@else{{ old('commission_2_company_amount') }}@endif" >
    </div>
    
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="commission_2_cheque_no">Check Nos</label>
        <input type="text" class="form-control text-capitalize" id="commission_2_cheque_no" name="commission_2_cheque_no" placeholder="Enter Check No"
        value="@if(isset($invoice)){{ $invoice->commission_2_cheque_no }}@else{{ old('commission_2_cheque_no') }}@endif" >
    </div>
    
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="commission_2_date">Date</label>
        <input type="text" class="form-control text-capitalize" id="commission_2_date" name="commission_2_date" placeholder="Enter Date"
        value="@if(isset($invoice)){{ $invoice->commission_2_date }}@else{{ old('commission_2_date', date('d/m/Y')) }}@endif" >
    </div>  
</div>
<h6>Commission 3</h6>
<hr/>
<div class="row">
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="commission_3_name">Name</label>
        <input type="text" class="form-control text-capitalize" id="commission_3_name" name="commission_3_name" placeholder="Enter Name"
        value="@if(isset($invoice)){{ $invoice->commission_3_name }}@else{{ old('commission_3_name') }}@endif" >
    </div>
    
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="commission_3_percentage">Percentage Commission</label>
        <input type="text" class="form-control text-capitalize" id="commission_3_percentage" name="commission_3_percentage" placeholder="Enter Percentage"
        value="@if(isset($invoice)){{ $invoice->commission_3_percentage }}@else{{ old('commission_3_percentage') }}@endif" >
    </div>
    
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="commission_3_amount">Amount</label>
        <input type="text" class="form-control text-capitalize" id="commission_3_amount" name="commission_3_amount" placeholder="Enter Amount"
        value="@if(isset($invoice)){{ $invoice->commission_3_amount }}@else{{ old('commission_3_amount') }}@endif" >
    </div>

    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="commission_3_tds">TDS %</label>
        <input type="text" class="form-control text-capitalize" id="commission_3_tds" name="commission_3_tds" placeholder="Enter TDS %"
        value="@if(isset($invoice)){{ $invoice->commission_3_tds }}@else{{ old('commission_3_tds') }}@endif" >
    </div>
    
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="commission_3_new">TDS Amount</label>
        <input type="text" class="form-control text-capitalize" id="commission_3_new" name="commission_3_new" placeholder="Enter TDS Amount"
        value="@if(isset($invoice)){{ $invoice->commission_3_new }}@else{{ old('commission_3_new') }}@endif" >
    </div>
    
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="commission_3_company_amount">Amount Paid</label>
        <input type="text" class="form-control text-capitalize" id="commission_3_company_amount" name="commission_3_company_amount" placeholder="Enter Amount Paid"
        value="@if(isset($invoice)){{ $invoice->commission_3_company_amount }}@else{{ old('commission_3_company_amount') }}@endif" >
    </div>
    
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="commission_3_cheque_no">Check Nos</label>
        <input type="text" class="form-control text-capitalize" id="commission_3_cheque_no" name="commission_3_cheque_no" placeholder="Enter Check No"
        value="@if(isset($invoice)){{ $invoice->commission_3_cheque_no }}@else{{ old('commission_3_cheque_no') }}@endif" >
    </div>
    
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="commission_3_date">Date</label>
        <input type="text" class="form-control text-capitalize" id="commission_3_date" name="commission_3_date" placeholder="Enter Date"
        value="@if(isset($invoice)){{ $invoice->commission_3_date }}@else{{ old('commission_3_date', date('d/m/Y')) }}@endif" >
    </div>  
</div>
<h6>Commission 4</h6>
<hr/>
<div class="row">
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="commission_4_name">Name</label>
        <input type="text" class="form-control text-capitalize" id="commission_4_name" name="commission_4_name" placeholder="Enter Name"
        value="@if(isset($invoice)){{ $invoice->commission_4_name }}@else{{ old('commission_4_name') }}@endif" >
    </div>
    
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="commission_4_percentage">Percentage Commission</label>
        <input type="text" class="form-control text-capitalize" id="commission_4_percentage" name="commission_4_percentage" placeholder="Enter Percentage"
        value="@if(isset($invoice)){{ $invoice->commission_4_percentage }}@else{{ old('commission_4_percentage') }}@endif" >
    </div>
    
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="commission_4_amount">Amount</label>
        <input type="text" class="form-control text-capitalize" id="commission_4_amount" name="commission_4_amount" placeholder="Enter Amount"
        value="@if(isset($invoice)){{ $invoice->commission_4_amount }}@else{{ old('commission_4_amount') }}@endif" >
    </div>

    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="commission_4_tds">TDS %</label>
        <input type="text" class="form-control text-capitalize" id="commission_4_tds" name="commission_4_tds" placeholder="Enter TDS %"
        value="@if(isset($invoice)){{ $invoice->commission_4_tds }}@else{{ old('commission_4_tds') }}@endif" >
    </div>
    
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="commission_4_new">TDS Amount</label>
        <input type="text" class="form-control text-capitalize" id="commission_4_new" name="commission_4_new" placeholder="Enter TDS Amount"
        value="@if(isset($invoice)){{ $invoice->commission_4_new }}@else{{ old('commission_4_new') }}@endif" >
    </div>
    
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="commission_4_company_amount">Amount Paid</label>
        <input type="text" class="form-control text-capitalize" id="commission_4_company_amount" name="commission_4_company_amount" placeholder="Enter Amount Paid"
        value="@if(isset($invoice)){{ $invoice->commission_4_company_amount }}@else{{ old('commission_4_company_amount') }}@endif" >
    </div>
    
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="commission_4_cheque_no">Check Nos</label>
        <input type="text" class="form-control text-capitalize" id="commission_4_cheque_no" name="commission_4_cheque_no" placeholder="Enter Check No"
        value="@if(isset($invoice)){{ $invoice->commission_4_cheque_no }}@else{{ old('commission_4_cheque_no') }}@endif" >
    </div>
    
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="commission_4_date">Date</label>
        <input type="text" class="form-control text-capitalize" id="commission_4_date" name="commission_4_date" placeholder="Enter Date"
        value="@if(isset($invoice)){{ $invoice->commission_4_date }}@else{{ old('commission_4_date', date('d/m/Y')) }}@endif" >
    </div>  
</div>
<h6>Commission 5</h6>
<hr/>
<div class="row">
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="commission_5_name">Name</label>
        <input type="text" class="form-control text-capitalize" id="commission_5_name" name="commission_5_name" placeholder="Enter Name"
        value="@if(isset($invoice)){{ $invoice->commission_5_name }}@else{{ old('commission_5_name') }}@endif" >
    </div>
    
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="commission_5_percentage">Percentage Commission</label>
        <input type="text" class="form-control text-capitalize" id="commission_5_percentage" name="commission_5_percentage" placeholder="Enter Percentage"
        value="@if(isset($invoice)){{ $invoice->commission_5_percentage }}@else{{ old('commission_5_percentage') }}@endif" >
    </div>
    
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="commission_5_amount">Amount</label>
        <input type="text" class="form-control text-capitalize" id="commission_5_amount" name="commission_5_amount" placeholder="Enter Amount"
        value="@if(isset($invoice)){{ $invoice->commission_5_amount }}@else{{ old('commission_5_amount') }}@endif" >
    </div>

    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="commission_5_tds">TDS %</label>
        <input type="text" class="form-control text-capitalize" id="commission_5_tds" name="commission_5_tds" placeholder="Enter TDS %"
        value="@if(isset($invoice)){{ $invoice->commission_5_tds }}@else{{ old('commission_5_tds') }}@endif" >
    </div>
    
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="commission_5_new">TDS Amount</label>
        <input type="text" class="form-control text-capitalize" id="commission_5_new" name="commission_5_new" placeholder="Enter TDS Amount"
        value="@if(isset($invoice)){{ $invoice->commission_5_new }}@else{{ old('commission_5_new') }}@endif" >
    </div>
    
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="commission_5_company_amount">Amount Paid</label>
        <input type="text" class="form-control text-capitalize" id="commission_5_company_amount" name="commission_5_company_amount" placeholder="Enter Amount Paid"
        value="@if(isset($invoice)){{ $invoice->commission_5_company_amount }}@else{{ old('commission_5_company_amount') }}@endif" >
    </div>
    
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="commission_5_cheque_no">Check Nos</label>
        <input type="text" class="form-control text-capitalize" id="commission_5_cheque_no" name="commission_5_cheque_no" placeholder="Enter Check No"
        value="@if(isset($invoice)){{ $invoice->commission_5_cheque_no }}@else{{ old('commission_5_cheque_no') }}@endif" >
    </div>
    
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="commission_5_date">Date</label>
        <input type="text" class="form-control text-capitalize" id="commission_5_date" name="commission_5_date" placeholder="Enter Date"
        value="@if(isset($invoice)){{ $invoice->commission_5_date }}@else{{ old('commission_5_date', date('d/m/Y')) }}@endif" >
    </div>  
</div>
<h6>Commission 6</h6>
<hr/>
<div class="row">
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="commission_6_name">Name</label>
        <input type="text" class="form-control text-capitalize" id="commission_6_name" name="commission_6_name" placeholder="Enter Name"
        value="@if(isset($invoice)){{ $invoice->commission_6_name }}@else{{ old('commission_6_name') }}@endif" >
    </div>
    
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="commission_6_percentage">Percentage Commission</label>
        <input type="text" class="form-control text-capitalize" id="commission_6_percentage" name="commission_6_percentage" placeholder="Enter Percentage"
        value="@if(isset($invoice)){{ $invoice->commission_6_percentage }}@else{{ old('commission_6_percentage') }}@endif" >
    </div>
    
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="commission_6_amount">Amount</label>
        <input type="text" class="form-control text-capitalize" id="commission_6_amount" name="commission_6_amount" placeholder="Enter Amount"
        value="@if(isset($invoice)){{ $invoice->commission_6_amount }}@else{{ old('commission_6_amount') }}@endif" >
    </div>

    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="commission_6_tds">TDS %</label>
        <input type="text" class="form-control text-capitalize" id="commission_6_tds" name="commission_6_tds" placeholder="Enter TDS %"
        value="@if(isset($invoice)){{ $invoice->commission_6_tds }}@else{{ old('commission_6_tds') }}@endif" >
    </div>
    
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="commission_6_new">TDS Amount</label>
        <input type="text" class="form-control text-capitalize" id="commission_6_new" name="commission_6_new" placeholder="Enter TDS Amount"
        value="@if(isset($invoice)){{ $invoice->commission_6_new }}@else{{ old('commission_6_new') }}@endif" >
    </div>
    
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="commission_6_company_amount">Amount Paid</label>
        <input type="text" class="form-control text-capitalize" id="commission_6_company_amount" name="commission_6_company_amount" placeholder="Enter Amount Paid"
        value="@if(isset($invoice)){{ $invoice->commission_6_company_amount }}@else{{ old('commission_6_company_amount') }}@endif" >
    </div>
    
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="commission_6_cheque_no">Check Nos</label>
        <input type="text" class="form-control text-capitalize" id="commission_6_cheque_no" name="commission_6_cheque_no" placeholder="Enter Check No"
        value="@if(isset($invoice)){{ $invoice->commission_6_cheque_no }}@else{{ old('commission_6_cheque_no') }}@endif" >
    </div>
    
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="commission_6_date">Date</label>
        <input type="text" class="form-control text-capitalize" id="commission_6_date" name="commission_6_date" placeholder="Enter Date"
        value="@if(isset($invoice)){{ $invoice->commission_6_date }}@else{{ old('commission_6_date', date('d/m/Y')) }}@endif" >
    </div>  
</div>
<div class="form-group">
    <button type="submit" class="btn btn-success">
        @if(isset($invoice))
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
 if ($( "#payment_mode  option:selected" ).text() == "Cheque") {
 other.style.visibility = "visible";
 }
 else {
 other.style.visibility = "hidden";
 }
 }

 
 const selector = '[data-x-wrapper]';
        let options = {
            disableNaming: '[data-disable-naming]',
            wrapper: selector,
            group: '[data-x-group]',
            addBtn: '[data-add-btn]',
            removeBtn: '[data-remove-btn]'
        };
 
        $(selector).replicate(options);
$(document).ready(function(){
  function calculateInvoiceAmounts() {
    var propertyAmount = parseFloat($('#property_amount').val()) || 0;
    var gstPercentage = parseFloat($('#gst_percentage').val()) || 0;
    var tdsPercentage = parseFloat($('#tds_percentage').val()) || 0;

    var gst = propertyAmount * (gstPercentage / 100);
    var tds = propertyAmount * (tdsPercentage / 100);
    var amount_for_developer = propertyAmount + gst;
    var amount_after_tds = propertyAmount - tds;

    // Use toFixed(2) to avoid long precision decimal problems, or simply leave it as is if they prefer exact JS floats. Let's do raw JS floats for compatibility, or toFixed.
    // However, JS floats might produce 0.0000000000004 type results, toFixed is safer. Let's use 2 decimal places.
    // Or just simple math:
    $('#gst').val(gst.toFixed(2));
    $('#amount_for_developer').val(amount_for_developer.toFixed(2));
    $('#tds').val(tds.toFixed(2));
    $('#amount_after_tds').val(amount_after_tds.toFixed(2));
    
    calculateAfterCustomerPassOn();
  }

  function calculateAfterCustomerPassOn() {
      var amountAfterTds = parseFloat($('#amount_after_tds').val()) || 0;
      var customerPassOn = parseFloat($('#customer_pass_on').val()) || 0;
      var afterPassOn = amountAfterTds - customerPassOn;
      $('#after_customer_passon').val(afterPassOn.toFixed(2));
      calculateCommissions();
  }

  function calculateCommissions() {
    var afterPassOn = parseFloat($('#after_customer_passon').val()) || 0;
    for (var i = 1; i <= 6; i++) {
        var percInput = $('#commission_' + i + '_percentage').val();
        if (percInput !== "" && percInput !== undefined) {
            // Remove % signs safely
            var percentage = parseFloat(percInput.replace('%','')) || 0;
            var commissionAmount = afterPassOn * (percentage / 100);
            
            var tdsInput = $('#commission_' + i + '_tds').val() || "";
            var tdsPercentage = parseFloat(tdsInput.replace('%','')) || 0;
            var newAmount = commissionAmount * (tdsPercentage / 100);
            var amountPaid = commissionAmount - newAmount;

            $('#commission_' + i + '_amount').val(commissionAmount.toFixed(2));
            $('#commission_' + i + '_new').val(newAmount.toFixed(2));
            $('#commission_' + i + '_company_amount').val(amountPaid.toFixed(2));
        }
    }
  }

  $("#property_amount, #gst_percentage, #tds_percentage").on("keyup change", function(){
    calculateInvoiceAmounts();
  });

  $("#customer_pass_on").on("keyup change", function(){
      calculateAfterCustomerPassOn();
  });

  var commissionSelectors = "#after_customer_passon";
  for (var i = 1; i <= 6; i++) {
      commissionSelectors += ", #commission_" + i + "_percentage, #commission_" + i + "_tds";
  }

  $(commissionSelectors).on("keyup change", function(){
      calculateCommissions();
  });
});
</script>
