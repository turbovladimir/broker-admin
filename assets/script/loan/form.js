/**
 * form behavior here
 */

$(document).ready(function () {
    $('#btn_send_sms').click(function () {
        const phone_input = $('#phone');
        const phone_val = phone_input.val();


        /**
         * move to validate.js
         */
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
})