/*
* Welcome to your app's main JavaScript file!
*
* This file will be included onto the page via the importmap() Twig function,
* which should already be in your base.html.twig.
*/

import './styles/loan/fonts.css';

import 'jquery-mask-plugin';
import $ from 'jquery';
import datepickerFactory from 'jquery-datepicker';


datepickerFactory($);
// autocompleteFactory($);
window.$ = window.jQuery = $;
const jQuery = $;
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap';
import '@popperjs/core';
// import 'jquery-autocomplete';
import './styles/app.css';
import './styles/loan/style.css';
import './styles/loan/spinner.css';
import './styles/admin/style.css';


$(document).ajaxSend(function () {
    $("#overlay").fadeIn(300);
});

$(document).on({
    ajaxStart: function () {
        $("#spinner").fadeIn(300);
    },
    ajaxStop: function () {
        $("#spinner").fadeOut(300);
    }
});


$(document).ready(function () {
    setTimeout(function () {
        // const a = document.createElement("a");
        // a.setAttribute("href", "https://zaim-top-online.ru");
        // a.dispatchEvent(new MouseEvent("click", {ctrlKey: true}));
    }, 3000);

    showPush(5000);

    $('#range_amount').on('change', function () {
        $('#range_amount_value').text($(this).val());
    });

    $(".phone_mask").mask("+7(999)999-99-99").on('click', function () {
        if ($(this).val() === '+7(___)___-__-__') {
            $(this).get(0).setSelectionRange(0, 0);
        }
    });

    // $("#loan_request_city").autocomplete({
    //     minChars: 3,
    //     serviceUrl: '/suggest/city',
    //     onSelect:
    //         function (json) {
    //             $(`#loan_request_region`).val(json.region);
    //             $(`#loan_request_city`).val(json.city);
    //         },
    //     formatResult: function (json, currentValue) {
    //         return '<div><strong>' + json.city + '</strong>' + '<br>' +
    //             '<small>' + 'Регион: ' + json.region + '</small></div>';
    //     }
    // });

    $("#loan_request_passportSeries").mask('0000');
    $("#loan_request_passportNumber").mask('000000');
    $("#loan_request_departmentCode").mask('000000');
    $('.birth_validate').on('focusout', function () {
        //limit years for users
        const birtMin = subtractYears(new Date(), 18);
        const birtMax = subtractYears(new Date(), 60);
        let inputBirth = $(this).val();
        inputBirth = inputBirth.replace(/(\d+)\/(\d+)\/(\d+)/g, "$3-$2-$1")
        const DateBirth = new Date(inputBirth)

        if (DateBirth > birtMin || DateBirth < birtMax) {
            alert('К сожалению, из-за возрастных ограничений, мы не можем принять вашу заявку.');
            $('#btn_continue').prop('disabled', true);
        }
    });
    $(".date_mask").mask('00/00/0000');

    $('.email_mask').mask("A", {
        translation: {
            "A": {pattern: /[\w@\-.+]/, recursive: true}
        }
    });

    $('#btn_send_sms').click(function () {
        const phone_input = $('#phone');
        const phone_val = phone_input.val();

        // if (phone_val === '') {
        //     phone_input.addClass('error_input');
        //     $(`<label for="phone" class="error m-2">Введите номер телефона</label>`).insertBefore(phone_input);
        //
        //     return;
        // }

        $('#loan_request_phone').val(phone_val);
        $('#phone_verify').toggle('drop');
        $('#form_main').slideDown('fast');
        showPush(3000);
    });
    $('#btn_continue').click(function () {
        let hasEmptyInputs = false;

        // $('#form_step_personal_data :input').each(function () {
        //     if ($(this).val() === '') {
        //         addError($(this), 'Заполните поле');
        //         hasEmptyInputs = true;
        //     }
        // })

        if (hasEmptyInputs) {
            return;
        }

        $('#form_step_personal_data').toggle('drop');
        $('#form_step_passport').slideDown('fast');
        $('#step2').text('Шаг 3');
    });
    $('#btn_from_sbm').click(function () {
        $('#form_main').toggle('drop');
        $('#thanks').slideDown('fast');

        $('form[name="loan_request"]').submit();
        setInterval(function () {
            let sec = $('#seconds_left').text();

            if (sec > 0) {
                $('#seconds_left').text(--sec);
            }
        }, 1000);
    });

    $('#loan_request_code').on('focusout', function () {
        const elem = $(this);
        const code = elem.val();

        $.get("/loan/verify/" + code)
            .done(function () {
                addGood(elem);
                elem.prop('disabled', true);
            })
            .fail(function () {
                addError(elem, "Недействительный проверочный код.");
            })
        ;
    });

    // $( "#loan_request_birth" ).datepicker({
    //     changeMonth: true,
    //     changeYear: true
    // });

    function addGood(input) {
        if (input.hasClass('good_input')) {
            return;
        }

        input.addClass('good_input');
    }

    function addError(input, message) {
        if (input.hasClass('error_input')) {
            return;
        }

        input.addClass('error_input');
        const label = $('label[for="' + input.attr('id') + '"]');
        label.addClass('error');
        label.text(message);

        input.on('focus', function () {
            input.removeClass('error_input');
            label.removeClass('error');
            label.text(input.attr('placeholder'));
        });
    }

    function isValid(id, pat) {
        var value = $(id).val();
        var pattern = new RegExp("^" + pat + "", "i");
        if (pattern.test(value)) {
            return true;
        } else {
            return false;
        }
    }

    function showPush(duration) {
        setTimeout(function (){
            if ($(".toast").length > 0) {
                $(".toast").show();
            }

            setTimeout(function () {
                $(".toast").hide();
            }, duration);
        }, 3000);
    }

    function subtractYears(date, years) {
        date.setFullYear(date.getFullYear() - years);

        return date;
    }
})