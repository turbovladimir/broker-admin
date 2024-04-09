$(function () {
    let phone = '';

    const code_div = $('#modal_body_code');

    function Timer(container_id, sec) {
        const downloadTimer = setInterval(function(){
            if(sec <= 0){
                clearInterval(downloadTimer);
                document.getElementById(container_id).innerHTML = "Повторить";
            } else {
                document.getElementById(container_id).innerHTML =
                    sec + " секунд до повторной отправки смс.";

                $(`#${container_id}`).on('click', function () {
                    Reset();
                    RequestCode();
                });
            }
            sec -= 1;
        }, 1000);
    }

    function RequestCode() {
        $.ajax({
            url: '/phone/verify/request',
            method: 'POST',
            data: {
                'phone' : phone
            }
        })
    }

    function Reset(e) {
        code_div.find('input').each(function () {
            $(this).val(undefined).removeClass('opacity-50').removeAttr('disabled');
        })
        $('.modal-block-error').hide();
        $('#modal-error').text('');
    }

    function goToNextInput(e) {
        var key = e.which,
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
        var key = e.which;

        if (key === 9 || (key >= 48 && key <= 57)) {
            return true;
        }

        e.preventDefault();
        return false;
    }

    function onFocus(e) {
        $(e.target).select();
    }

    code_div.on('keyup', 'input', goToNextInput);
    code_div.on('keydown', 'input', onKeyDown);
    code_div.on('click', 'input', onFocus);
    // code_div.on('click', '#reset_code', Reset);
    code_div.on('change', 'input', function () {
        let code = [];

        code_div.find('input').each(function () {
            let val = $(this).val();

            if (val === '') {
                return;
            }

            code.push(val);
        })

        if (code.length < 4) {
            return;
        }

        const $form = $('form[name="code_verify"]');
        $form.find('input[name="phone"]').val(phone);
        $form.siblings('input').addClass('opacity-50').attr('disabled', true);

        $.ajax({
            url: $form.attr('action'),
            method: 'POST',
            data: $form.serialize(),
        })
            .fail(function( data ) {

                if (data.responseJSON.error !== undefined) {
                    $('#modal-error').text(data.responseJSON.error);
                    const $more_try = $('<a type="button" aria-disabled="true"  id="code_more_try"></a>');
                    Timer('code_more_try',10);
                    $('.modal-block-error').append($more_try);
                    $('.modal-block-error').show();
                } else {
                    alert('Что то пошло не так...');
                }

                console.log(data);
            })
            .done(function (response) {
                console.log(response);
                $('.modal-close').find('button').trigger('click');
                window.location.replace('/loan/form');
            })
        ;
    });

    $('#btn_send_sms').on('click', function () {
        const button = this;
        const phone_input = $('#phone');
        phone = phone_input.val();


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
        RequestCode();

        $('#modal_body_phone_verify_request').addClass("d-none");
        $('#modal_body_code').removeClass("d-none");
    });
})