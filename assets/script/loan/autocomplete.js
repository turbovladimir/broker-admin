import 'devbridge-autocomplete';
$(document).ready(function () {
    $(".suggest_address").autocomplete({
        minChars: 5,
        serviceUrl: '/suggest/address',
        formatResult: function (json) {

            return '<div><a type="button" href="#"><strong>' + json.value + '</strong>' +
                '</a></div>';
        },
    });
    $(".suggest_city").autocomplete({
        minChars: 3,
        serviceUrl: '/suggest/city',
        formatResult: function (json) {

            return '<div><a type="button" href="#"><strong>' + json.value + '</strong>' +
                '</a></div>';
        },
    });
    $(".suggest_dep_code").autocomplete({
        minChars: 7,
        serviceUrl: '/suggest/department_code',
        formatResult: function (json, currentValue) {

            return '<div><a type="button" href="#"><strong>' + json.value + '</strong>' + '<br>' +
                '<small>' + 'Выдан: ' + json.department + '</small></a></div>';
        },
        onSelect:
            function (json) {
                $(`#loan_request_department`).val(json.department);
            },
    });
})
