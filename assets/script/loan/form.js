import {addError} from './validate.js';
/**
 * form behavior here
 */

$(function () {
    $('#btn_continue').on('click', function () {
        let hasEmptyInputs = false;

        $('#form_step_personal_data :input').each(function () {
            if ($(this).val() === '') {
                addError($(this), 'Заполните поле');
                hasEmptyInputs = true;
            }
        })

        if (hasEmptyInputs) {
            return;
        }

        $('#form_step_personal_data').toggle('drop');
        $('#form_step_passport').slideDown('fast');
        $('#step2').text('Шаг 3');
    });
    $('#btn_from_sbm').on('click',function () {
        let hasEmptyInputs = false;

        $('#form_step_passport :input').each(function () {
            if ($(this).val() === '') {
                addError($(this), 'Заполните поле');
                hasEmptyInputs = true;
            }
        })

        if (hasEmptyInputs) {
            return;
        }

        $(document).off('ajaxStart');
        $(document).off('ajaxStop');

        const $form = $('form[name="loan_request"]');

        $.ajax({
            url: $form.attr('action'),
            method: 'POST',
            data: $form.serialize(),
            beforeSend: function() {
                    $('#form_main').toggle('drop');
                    $('#thanks').toggle();

                    setInterval(function () {
                        let number = $('#seconds_left');
                        let sec = number.text();

                        if (sec > 0) {
                            number.text(--sec);
                        }
                    }, 1000);
            }
        })
            .fail(function( data ) {
                alert('Что то пошло не так...');

                console.log(data);
            })
            .done(function (response) {
                const thanksDiv = $('#thanks');
                thanksDiv.toggle('drop');

                const f = function () {
                    console.log(response.offer_list);

                    return $('<div id="offer_list" class="row mt-4 p-2">'+ response.offer_list +'</div>').insertAfter(thanksDiv);
                }

                $.when(f()).done(function () {
                    $('.offer_wrap').fadeIn('fast');

                    $('.offer_wrap').children().each(function (i) {
                        $(this)
                            .fadeIn((i + 1) * 1000);
                    })
                })
            })
        ;
    });
})