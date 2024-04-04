$(function () {
    $('#btn_send_sms').on('click', function () {
        const phone_input = $('#phone');
        const phone_val = phone_input.val();


        /**
         * move to validate.js
         */
        if (phone_val === '') {
            phone_input.addClass('error_input');
            $(`<label for="phone" class="error m-2">Введите номер телефона</label>`).insertBefore(phone_input);

            return;
        }

        $.ajax({
            url: '/phone/verify/request',
            method: 'POST',
            data: {
                'phone' : phone_val
            },
        })
            .done(function (response) {
                console.log(response);
                $('#loan_request_phone').val(phone_val);
                $('#phone_verify').toggle('drop');
                $('#form_main').slideDown('fast');
            })
    });
})