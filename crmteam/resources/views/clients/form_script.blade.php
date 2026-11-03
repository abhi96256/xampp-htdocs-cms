<script src="{{url('/').'/js/jquery.replicate.js'}}"></script>

<script>
$(document).ready(function() {
    // Phone number numeric validation
    $("input[name='contact_no']").on('input', function (e) {
        $(this).val($(this).val().replace(/[^0-9]/g, ''));
    });

    // Replicator initialization
    const selector = '[data-x-wrapper]';
    let options = {
        disableNaming: '[data-disable-naming]',
        wrapper: selector,
        group: '[data-x-group]',
        addBtn: '[data-add-btn]',
        removeBtn: '[data-remove-btn]'
    };
    if ($(selector).length) {
        $(selector).replicate(options);
    }
});

function otherSelect(event) {
    var other = document.getElementById("otherBox");
    if ($("#payment_mode option:selected").text() == "Cheque") {
        other.style.visibility = "visible";
    } else {
        other.style.visibility = "hidden";
    }
}
</script>
