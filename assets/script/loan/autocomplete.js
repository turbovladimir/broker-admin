import 'devbridge-autocomplete';

$(() => {
    AutoComplete.Init();
})
const AutoComplete  = {
    Init: function () {
        $(".suggest_address").devbridgeAutocomplete({
            minChars: 5,
            serviceUrl: '/suggest/address',
            formatResult: function (json) {

                return '<div class="mb-3"><a type="button" href="#"><p>' + json.value + '</p>' +
                    '</a></div>';
            },
        });
        $(".suggest_city").devbridgeAutocomplete({
            minChars: 3,
            serviceUrl: '/suggest/city',
            formatResult: function (json) {

                return '<div><a type="button" href="#"><strong>' + json.value + '</strong>' +
                    '</a></div>';
            },
        });
        $(".suggest_dep_code").devbridgeAutocomplete({
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
    }
}