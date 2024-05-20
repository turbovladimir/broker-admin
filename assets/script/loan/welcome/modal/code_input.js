import {Params, WrongCodeHandler} from "./wrong_code.js";

const
    form = $('form[name="code_verify"]'),
    inputs = $('.code'),
    inputPhone = $('input[name="phone"]');

const CodeInputHandler = {
    Handle: function () {
        this.autocompleteFromSms();
        inputs.on({
            'verify' : event => {
                this.verifyCode();
                },
            'keyup': event => {
                this.handleInputs(event);
                },
            'keydown': event => {
                let key = event.which,
                    t = $(event.target);

                //prevent input more than one number in cell
                if (t.val() !== '' && key >= 48 && key <= 57) {
                    event.preventDefault();
                }
            }
        });
    },
    autocompleteFromSms: () => {
        $('#one-time-code').on('input', (e) => {

            const code = $(e.target).val();
            if (code.length === 4) {
                $('.code').each(function (index, element) {
                    $(element).val(code.charAt(index));
                })

                inputs.first().trigger('verify');
            }
        })
    },
    handleInputs: function (e) {
        let key = e.which,
            t = $(e.target);

        if (key >= 48 && key <= 57) {

            if (inputs.filter(function() { return $(this).val() !== ""; }).length === 4) {
                inputs.first().trigger('verify');
            } else  {
                t.parent().next().children().trigger('focus');
            }
        } else if (key === 8) {
            t.val();
            t.parent().prev().children().trigger('focus');
        } else {
            e.preventDefault();
        }
    },
    verifyCode: function () {
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
                    .attr('disabled', true)
                    .trigger('input_code_success')
                ;
                window.location.replace('/loan/form');
            })
        ;
    }
}

export {CodeInputHandler}