$(document).ready(function() {
    $('.js-example-basic-single').select2({
        width: 'element',
    });

    // Initialize Select2 with tags for Incentive Percentages
    $('.incentive-select2').select2({
        width: 'element',
        tags: true,
        placeholder: "Select or type percent"
    });

    // Initialize Select2 for Closed By (NO tags - use preset options only so data-ancestors is available)
    $('#closed_by_select').select2({
        placeholder: "Select user",
        allowClear: true,
        width: '100%'
    });
});
