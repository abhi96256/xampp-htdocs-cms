import sys

file_path = r"c:\xampp\htdocs\cms\crm\resources\views\invoices\form.blade.php"
with open(file_path, "r") as f:
    lines = f.readlines()

invoice_details = """<div class="row">
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
</div>\n"""

def get_commission_html(num):
    return f"""<div class="row">
    <div class="form-group col-sm-4">
        <label class="text-capitalize" for="commission_{num}_name">Name</label>
        <input type="text" class="form-control text-capitalize" id="commission_{num}_name" name="commission_{num}_name" placeholder="Enter Name"
        value="@if(isset($invoice)){{{{ $invoice->commission_{num}_name }}}}@else{{{{ old('commission_{num}_name') }}}}@endif" >
    </div>
	
	<div class="form-group col-sm-4">
        <label class="text-capitalize" for="commission_{num}_amount">Amount</label>
        <input type="text" class="form-control text-capitalize" id="commission_{num}_amount" name="commission_{num}_amount" placeholder="Enter Amount"
        value="@if(isset($invoice)){{{{ $invoice->commission_{num}_amount }}}}@else{{{{ old('commission_{num}_amount') }}}}@endif" >
    </div>

	<div class="form-group col-sm-4">
        <label class="text-capitalize" for="commission_{num}_tds">TDS Amount</label>
        <input type="text" class="form-control text-capitalize" id="commission_{num}_tds" name="commission_{num}_tds" placeholder="Enter TDS Amount"
        value="@if(isset($invoice)){{{{ $invoice->commission_{num}_tds }}}}@else{{{{ old('commission_{num}_tds') }}}}@endif" >
    </div>
	<div class="form-group col-sm-4">
        <label class="text-capitalize" for="commission_{num}_company_amount">Amount Paid</label>
        <input type="text" class="form-control text-capitalize" id="commission_{num}_company_amount" name="commission_{num}_company_amount" placeholder="Enter Amount Paid"
        value="@if(isset($invoice)){{{{ $invoice->commission_{num}_company_amount }}}}@else{{{{ old('commission_{num}_company_amount') }}}}@endif" >
    </div>
	<div class="form-group col-sm-4">
        <label class="text-capitalize" for="commission_{num}_cheque_no">Check Nos</label>
        <input type="text" class="form-control text-capitalize" id="commission_{num}_cheque_no" name="commission_{num}_cheque_no" placeholder="Enter Check No"
        value="@if(isset($invoice)){{{{ $invoice->commission_{num}_cheque_no }}}}@else{{{{ old('commission_{num}_cheque_no') }}}}@endif" >
    </div>
	<div class="form-group col-sm-4">
        <label class="text-capitalize" for="commission_{num}_date">Date</label>
        <input type="text" class="form-control text-capitalize" id="commission_{num}_date" name="commission_{num}_date" placeholder="Enter Date"
        value="@if(isset($invoice)){{{{ $invoice->commission_{num}_date }}}}@else{{{{ old('commission_{num}_date', date('d/m/Y')) }}}}@endif" >
    </div>  
</div>\n"""

new_lines = []
new_lines.extend(lines[:13])
new_lines.append(invoice_details)
new_lines.extend(lines[170:173])
new_lines.append(get_commission_html(1))
new_lines.extend(lines[237:240])
new_lines.append(get_commission_html(2))
new_lines.extend(lines[304:307])
new_lines.append(get_commission_html(3))
new_lines.extend(lines[373:])

with open(file_path, "w") as f:
    f.writelines(new_lines)

print("Done")
