import 'devbridge-autocomplete';
$(document).ready(function () {
    $(".suggest_address").autocomplete({
        minChars: 5,
        serviceUrl: '/suggest/address',
        // formatResult: function (json, currentValue) {
        //     console.log(json);
        //     console.log(currentValue);
        //
        //     return '<div><strong>' + json.city + '</strong>' + '<br>' +
        //         '<small>' + 'Регион: ' + json.region + '</small></div>';
        // }
    });
    $(".suggest_city").autocomplete({
        minChars: 3,
        serviceUrl: '/suggest/city',
    });
    $(".suggest_dep_code").autocomplete({
        minChars: 7,
        serviceUrl: '/suggest/department_code',
        onSelect:
            function (json) {
                $(`#loan_request_department`).val(json.department);
            },
    });
})
