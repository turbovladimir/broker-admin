import {Params, WrongCodeHandler} from "./wrong_code.js";

const
    form = $('form[name="code_verify"]'),
    inputs = $('.code'),
    inputPhone = $('input[name="phone"]');

const CodeInputHandler = {
    Handle: function () {
        inputs.on('keyup', event => {
            this.handleInputs(event);
            this.verifyCode();
        });
    },
    handleInputs: function (e) {
        let key = e.which,
            t = $(e.target);

        if (key >= 48 && key <= 57) {
            t.parent().next().children().trigger('focus');
        } else if (key === 8) {
            t.val();
            t.parent().prev().children().trigger('focus');
        } else {
            e.preventDefault();
        }
    },
    verifyCode: function () {
        let code = [];

        inputs.each(function () {
            let val = $(this).val();

            if (val === '') {
                return;
            }

            code.push(val);
        })

        if (code.length < 4) {
            return;
        }

        inputPhone.val($('#phone').val());
        form
            .siblings('input')
            .addClass('opacity-50')
            .attr('disabled', true);

        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
        })
            .fail(function (data) {

                if (data.responseJSON.error !== undefined) {
                    let params = new Params(
                        data.responseJSON.error,
                        $('#code_row'),
                    );

                    WrongCodeHandler.Handle(params);
                } else {
                    alert('Что то пошло не так...');
                }
            })
            .done(function (response) {
                $('.code')
                    .addClass('code_success')
                    .attr('disabled', true);
                window.location.replace('/loan/form');
            })
        ;
    }
}

export {CodeInputHandler}