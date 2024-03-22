import 'jquery-mask-plugin';
$(document).ready(function () {
    $(".phone_mask").mask("+7(999)999-99-99").on('click', function () {
        if ($(this).val() === '+7(___)___-__-__') {
            $(this).get(0).setSelectionRange(0, 0);
        }
    });
    $("#loan_request_passportSeries").mask('0000');
    $("#loan_request_passportNumber").mask('000000');
    $("#loan_request_departmentCode").mask('000000');
    $(".date_mask").mask('00/00/0000');
    $('.email_mask').mask("A", {
        translation: {
            "A": {pattern: /[\w@\-.+]/, recursive: true}
        }
    });
})