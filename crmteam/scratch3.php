<?php
$file = 'c:\xampp\htdocs\cms\crm\resources\views\invoices\form.blade.php';
$content = file_get_contents($file);

$search = <<<'JS'
  $("#property_amount, #gst_percentage, #tds_percentage").on("keyup change", function(){
    calculateInvoiceAmounts();
  });
});
</script>
JS;

$replace = <<<'JS'
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

  var commissionSelectors = "#after_customer_passon";
  for (var i = 1; i <= 6; i++) {
      commissionSelectors += ", #commission_" + i + "_percentage";
  }

  $(commissionSelectors).on("keyup change", function(){
      calculateCommissions();
  });
});
</script>
JS;

$content = str_replace("\r\n", "\n", $content);
$search = str_replace("\r\n", "\n", $search);

if (strpos($content, $search) !== false) {
    $content = str_replace($search, $replace, $content);
    file_put_contents($file, $content);
    echo "Replaced successfully!\n";
} else {
    echo "Search string not found.\n";
}
