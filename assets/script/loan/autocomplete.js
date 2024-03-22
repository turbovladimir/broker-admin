import 'jquery-ui';
$(document).ready(function () {
    $("#loan_request_city").autocomplete({
        minChars: 3,
        serviceUrl: '/suggest/city',
        onSelect:
            function (json) {
                $(`#loan_request_region`).val(json.region);
                $(`#loan_request_city`).val(json.city);
            },
        formatResult: function (json, currentValue) {
            return '<div><strong>' + json.city + '</strong>' + '<br>' +
                '<small>' + 'Регион: ' + json.region + '</small></div>';
        }
    });
})
