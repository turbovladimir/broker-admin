$(function () {

    const PhoneVerifyWidget = {
        phone: '',
        form: null,
        inputsCodeNumber: null,
        inputPhone : null,
        init: function () {
            this.form = $('form[name="code_verify"]');
            this.inputsCodeNumber = this.form.find('input[name="code\[]\"]');
            this.inputPhone = this.form.find('input[name="phone"]');
            this.addListeners();
        },
        addListeners: function () {
            $('#btn_send_sms').on('click', validatePhoneAndRequestCode);
            this.inputsCodeNumber.each(function () {
                const $this = $(this);
                $this.on('keyup', goToNextInput);
                $this.on('keydown', onKeyDown);
                $this.on('click', onFocus);
            })

            this.inputsCodeNumber.last().on('keyup', verifyCode);
        },
        requestCode: function (phone) {
            $.ajax({
                url: '/phone/verify/request',
                method: 'POST',
                data: {
                    'phone': phone
                }
            })
        },
        resetCode: function () {
            this.inputsCodeNumber.each(function () {
                $(this)
                    .val(undefined)
                    .removeClass('opacity-50')
                    .removeAttr('disabled');
            })
            $('.modal-block-error').hide();
            $('#modal-error').text('');
        },
        createTimer: function (container_id, sec) {
            const widget = this;
            const downloadTimer = setInterval(function () {
                if (sec <= 0) {
                    clearInterval(downloadTimer);
                    document.getElementById(container_id).innerHTML = "Повторить";
                } else {
                    document.getElementById(container_id).innerHTML =
                        sec + " секунд до повторной отправки смс.";

                    $(`#${container_id}`).on('click', function () {
                        widget.resetCode();
                        widget.requestCode($('#phone').val());
                    });
                }
                sec -= 1;
            }, 1000);
        },
    };

    PhoneVerifyWidget.init();

    function verifyCode () {
        let code = [];

        PhoneVerifyWidget.inputsCodeNumber.each(function () {
            let val = $(this).val();

            if (val === '') {
                return;
            }

            code.push(val);
        })

        if (code.length < 4) {
            return;
        }

        PhoneVerifyWidget.inputPhone.val($('#phone').val());
        PhoneVerifyWidget.form
            .siblings('input')
            .addClass('opacity-50')
            .attr('disabled', true);

        $.ajax({
            url: PhoneVerifyWidget.form.attr('action'),
            method: 'POST',
            data: PhoneVerifyWidget.form.serialize(),
        })
            .fail(function (data) {

                if (data.responseJSON.error !== undefined) {
                    $('#modal-error').text(data.responseJSON.error);
                    const $more_try = $('<a type="button" aria-disabled="true"  id="code_more_try"></a>');
                    $('.modal-block-error').append($more_try);
                    PhoneVerifyWidget.createTimer('code_more_try', 10);
                    $('.modal-block-error').show();
                } else {
                    alert('Что то пошло не так...');
                }
            })
            .done(function (response) {
                PhoneVerifyWidget.inputsCodeNumber
                    .addClass('code_success')
                    .attr('disabled', true);
                $('.modal-close')
                    .find('button')
                    .trigger('click');
                window.location.replace('/loan/form');
            })
        ;
    }
    function validatePhoneAndRequestCode (e) {
        const button = e.target;
        const phone_input = $('#phone');
        const phone = phone_input.val();


        /**
         * move to validate.js
         */
        if (phone === '') {
            phone_input.addClass('error_input');
            $(`<label for="phone" class="error m-2">Введите номер телефона</label>`).insertBefore(phone_input);

            return;
        }

        button.setAttribute('disabled', true);
        $('#phone_number').text(phone);
        PhoneVerifyWidget.requestCode(phone);

        $('#modal_body_phone_verify_request').addClass("d-none");
        $('#modal_body_code').removeClass("d-none");
    }


    function goToNextInput(e) {
        let key = e.which,
            t = $(e.target),
            sib = t.next('input');

        if (key != 9 && (key < 48 || key > 57)) {
            e.preventDefault();
            return false;
        }

        if (key === 9) {
            return true;
        }

        if (!sib || !sib.length) {
            sib = code_div.find('input').eq(0);
        }
        sib.select().focus();
    }

    function onKeyDown(e) {
        const key = e.which;

        if (key === 9 || (key >= 48 && key <= 57)) {
            return true;
        }

        e.preventDefault();
        return false;
    }

    function onFocus(e) {
        $(e.target).select();
    }
})