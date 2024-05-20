import 'jquery-ui/dist/jquery-ui.min.js'
import {CodeRequester} from "./code_request.js";

function Params(errorMessage, elemShouldShake) {
    this.errorMessage= errorMessage
    this.elemShouldShake= elemShouldShake
    this.resetDelayTimeSec = 10
}

/**
 * @param {Params} params
 */
const WrongCodeHandler = {
    showError: function (errorMessage, elemShouldShake) {
        elemShouldShake.effect('shake');

        if ($('#code_more_try').length === 0) {
            $(`<div class="row row-cols-1 g-3">
                        <div id="code-error-message" class="col"><p class="error-text"></p></div>
                        <div id="code_more_try" class="col"><p></p></div>
                        </div>`).appendTo('#modal-block-error');
        }
        $('#code-error-message').children('p').text(errorMessage);
        $('#modal-block-error').show();
    },
    resetCode: function () {
        $('.code').val(undefined)
            .removeClass('opacity-50')
            .removeAttr('disabled')
    },
    sendCodeAgain: function () {
        $('#modal-block-error').empty();
        CodeRequester.MakeRequest($('#phone').val());
    },
    createTimer: function (sec) {
        const handler = this;

        const downloadTimer = setInterval(function () {
            if (sec <= 0) {
                clearInterval(downloadTimer);
                $('#code_more_try').empty();
                $('<button type="button" class="loan-btn gradient-green">Повторить отправку</button>')
                    .on('click', function () {
                        handler.sendCodeAgain()
                    })
                    .appendTo('#code_more_try');

                return;
            } else {
                $('#code_more_try').children('p').text(`${sec} секунд до повторной отправки смс.`)
            }
            sec -= 1;
        }, 1000);
    },
    Handle: function(params) {
        this.showError(params.errorMessage, params.elemShouldShake);
        this.resetCode();
        this.createTimer(params.resetDelayTimeSec);
        $('.code').trigger('input_code_fail');
    }
}

export {WrongCodeHandler, Params}