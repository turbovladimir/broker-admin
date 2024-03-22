$(document).ready(function () {
    $('#range_amount').on('change', function () {
        $('#range_amount_value').text($(this).val());
    });
})
