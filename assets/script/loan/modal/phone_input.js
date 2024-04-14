import {CodeRequester} from "./code_request.js";

const inputPhone = $('input[name="phone"]');

const PhoneInputHandler = {
    Handle: function () {
        $('#btn_send_sms').on('click',
                event => {
                    const phone = $('#phone').val();

                    if (phone === '') {
                        inputPhone.addClass('error_input');
                        $(`<label for="phone" class="error-text m-2">Введите номер телефона</label>`).insertBefore(inputPhone);
                        event.preventDefault();
                    } else {
                        event.target.setAttribute('disabled', true);
                        $('#phone_number').text(phone);
                        CodeRequester.MakeRequest(phone);
                        $('#modal_body_phone_verify_request').addClass("d-none");
                        $('#modal_body_code').removeClass("d-none");
                    }
        });
    }
}

export {PhoneInputHandler}