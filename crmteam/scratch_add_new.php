<?php
$file = 'c:\xampp\htdocs\cms\crmteam\resources\views\invoices\form.blade.php';
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
        <label class="text-capitalize" for="commission_{$i}_tds">TDS %</label>
        <input type="text" class="form-control text-capitalize" id="commission_{$i}_tds" name="commission_{$i}_tds" placeholder="Enter TDS %"
        value="@if(isset(\$invoice)){{ \$invoice->commission_{$i}_tds }}@else{{ old('commission_{$i}_tds') }}@endif" >
    </div>
    
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="commission_{$i}_new">New</label>
        <input type="text" class="form-control text-capitalize" id="commission_{$i}_new" name="commission_{$i}_new" placeholder="Enter New Amount"
        value="@if(isset(\$invoice)){{ \$invoice->commission_{$i}_new }}@else{{ old('commission_{$i}_new') }}@endif" >
    </div>
    
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="commission_{$i}_company_amount">Amount Paid</label>
        <input type="text" class="form-control text-capitalize" id="commission_{$i}_company_amount" name="commission_{$i}_company_amount" placeholder="Enter Amount Paid"
        value="@if(isset(\$invoice)){{ \$invoice->commission_{$i}_company_amount }}@else{{ old('commission_{$i}_company_amount') }}@endif" >
    </div>
    
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="commission_{$i}_cheque_no">Check Nos</label>
        <input type="text" class="form-control text-capitalize" id="commission_{$i}_cheque_no" name="commission_{$i}_cheque_no" placeholder="Enter Check No"
        value="@if(isset(\$invoice)){{ \$invoice->commission_{$i}_cheque_no }}@else{{ old('commission_{$i}_cheque_no') }}@endif" >
    </div>
    
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="commission_{$i}_date">Date</label>
        <input type="text" class="form-control text-capitalize" id="commission_{$i}_date" name="commission_{$i}_date" placeholder="Enter Date"
        value="@if(isset(\$invoice)){{ \$invoice->commission_{$i}_date }}@else{{ old('commission_{$i}_date', $dateDef) }}@endif" >
    </div>  
</div>

HTML;
}

file_put_contents($file, $before . $commissionsHtml . $after);
echo "Updated form.blade.php HTML\n";

// Update JS Logic in the same file
$content = file_get_contents($file);
$search = <<<'JS'
  function calculateCommissions() {
    var afterPassOn = parseFloat($('#after_customer_passon').val()) || 0;
    for (var i = 1; i <= 6; i++) {
        var percInput = $('#commission_' + i + '_percentage').val();
        if (percInput !== "" && percInput !== undefined) {
            var percentage = parseFloat(percInput) || 0;
            var commissionAmount = afterPassOn * (percentage / 100);
            $('#commission_' + i + '_amount').val(commissionAmount.toFixed(2));
            $('#commission_' + i + '_company_amount').val(commissionAmount.toFixed(2));
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
      commissionSelectors += ", #commission_" + i + "_percentage";
  }

  $(commissionSelectors).on("keyup change", function(){
      calculateCommissions();
  });
JS;

$replace = <<<'JS'
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
JS;

$content = str_replace("\r\n", "\n", $content);
$search = str_replace("\r\n", "\n", $search);
if (strpos($content, $search) !== false) {
    $content = str_replace($search, $replace, $content);
    file_put_contents($file, $content);
    echo "Updated form.blade.php JS\n";
} else {
    echo "JS Search string not found in form.blade.php\n";
}

// Update Invoices.php (Model)
$modelFile = 'c:\xampp\htdocs\cms\crmteam\app\Models\Invoices.php';
$modelContent = file_get_contents($modelFile);

$addFields = array();
for($i=1; $i<=6; $i++){
    $addFields[] = "'commission_{$i}_new'";
}
$fieldsStr = implode(", ", $addFields);

if (strpos($modelContent, "'commission_1_new'") === false) {
    $modelContent = str_replace("'commission_1_percentage',", "$fieldsStr, 'commission_1_percentage',", $modelContent);
    file_put_contents($modelFile, $modelContent);
    echo "Updated Invoices.php\n";
} else {
    echo "Invoices.php already updated\n";
}

// Update InvoicesController.php
$controllerFile = 'c:\xampp\htdocs\cms\crmteam\app\Http\Controllers\InvoicesController.php';
$controllerContent = file_get_contents($controllerFile);

for($i=1; $i<=6; $i++){
    $insert = "\n                'commission_{$i}_new' => \$request->input('commission_{$i}_new'),";
    if (strpos($controllerContent, "'commission_{$i}_new'") === false) {
        $controllerContent = str_replace("'commission_{$i}_tds' => \$request->input('commission_{$i}_tds'),", "'commission_{$i}_tds' => \$request->input('commission_{$i}_tds')," . $insert, $controllerContent);
    }
}
file_put_contents($controllerFile, $controllerContent);
echo "Updated InvoicesController.php\n";

// Generate SQL query
$queries = [];
for($i=1; $i<=6; $i++){
    $queries[] = "ADD commission_{$i}_new DECIMAL(8,2) NULL AFTER commission_{$i}_tds";
}
$queryString = "ALTER TABLE invoice \n" . implode(",\n", $queries) . ";";
file_put_contents('c:\xampp\htdocs\cms\crmteam\scratch_query_new.txt', $queryString);
echo "Written query to scratch_query_new.txt\n";
