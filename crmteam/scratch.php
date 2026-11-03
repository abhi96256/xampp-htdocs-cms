<?php
$file = 'c:\xampp\htdocs\cms\crm\resources\views\invoices\form.blade.php';
$content = file_get_contents($file);

$startMarker = '<h4>Commission Details</h4>';
$endMarker = '<div class="form-group">';

$posStart = strpos($content, $startMarker);
$posEnd = strpos($content, $endMarker, $posStart);

$before = substr($content, 0, $posStart + strlen($startMarker)) . "\n";
$after = substr($content, $posEnd);

$commissionsHtml = "";

for ($i = 1; $i <= 6; $i++) {
    $dateDef = "date('d/m/Y')";
$commissionsHtml .= <<<HTML
<h6>Commission $i</h6>
<hr/>
<div class="row">
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="commission_{$i}_name">Name</label>
        <input type="text" class="form-control text-capitalize" id="commission_{$i}_name" name="commission_{$i}_name" placeholder="Enter Name"
        value="@if(isset(\$invoice)){{ \$invoice->commission_{$i}_name }}@else{{ old('commission_{$i}_name') }}@endif" >
    </div>
    
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="commission_{$i}_percentage">Percentage Commission</label>
        <input type="text" class="form-control text-capitalize" id="commission_{$i}_percentage" name="commission_{$i}_percentage" placeholder="Enter Percentage"
        value="@if(isset(\$invoice)){{ \$invoice->commission_{$i}_percentage }}@else{{ old('commission_{$i}_percentage') }}@endif" >
    </div>
    
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="commission_{$i}_amount">Amount</label>
        <input type="text" class="form-control text-capitalize" id="commission_{$i}_amount" name="commission_{$i}_amount" placeholder="Enter Amount"
        value="@if(isset(\$invoice)){{ \$invoice->commission_{$i}_amount }}@else{{ old('commission_{$i}_amount') }}@endif" >
    </div>

    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="commission_{$i}_tds">TDS Amount</label>
        <input type="text" class="form-control text-capitalize" id="commission_{$i}_tds" name="commission_{$i}_tds" placeholder="Enter TDS Amount"
        value="@if(isset(\$invoice)){{ \$invoice->commission_{$i}_tds }}@else{{ old('commission_{$i}_tds') }}@endif" >
    </div>
    
    <div class="form-group col-sm-4">
        <label class="text-capitalize" for="commission_{$i}_company_amount">Amount Paid</label>
        <input type="text" class="form-control text-capitalize" id="commission_{$i}_company_amount" name="commission_{$i}_company_amount" placeholder="Enter Amount Paid"
        value="@if(isset(\$invoice)){{ \$invoice->commission_{$i}_company_amount }}@else{{ old('commission_{$i}_company_amount') }}@endif" >
    </div>
    
    <div class="form-group col-sm-4">
        <label class="text-capitalize" for="commission_{$i}_cheque_no">Check Nos</label>
        <input type="text" class="form-control text-capitalize" id="commission_{$i}_cheque_no" name="commission_{$i}_cheque_no" placeholder="Enter Check No"
        value="@if(isset(\$invoice)){{ \$invoice->commission_{$i}_cheque_no }}@else{{ old('commission_{$i}_cheque_no') }}@endif" >
    </div>
    
    <div class="form-group col-sm-4">
        <label class="text-capitalize" for="commission_{$i}_date">Date</label>
        <input type="text" class="form-control text-capitalize" id="commission_{$i}_date" name="commission_{$i}_date" placeholder="Enter Date"
        value="@if(isset(\$invoice)){{ \$invoice->commission_{$i}_date }}@else{{ old('commission_{$i}_date', $dateDef) }}@endif" >
    </div>  
</div>

HTML;
}

file_put_contents($file, $before . $commissionsHtml . $after);
echo "Successfully updated form.blade.php\n";
