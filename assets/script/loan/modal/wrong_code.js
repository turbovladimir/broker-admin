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
            $(`<div class="row row-cols-1">
                        <div id="code-error-message" class="col error-text"><span></span></div>
                        <div id="code_more_try" class="col"><span></span></div>
                        </div>`).appendTo('#modal-block-error');
        }
        $('#code-error-message').children('span').text(errorMessage);
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
                $(`
                    <button type="button" class="btn btn-success">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-counterclockwise" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M8 3a5 5 0 1 1-4.546 2.914.5.5 0 0 0-.908-.417A6 6 0 1 0 8 2z"></path>
  <path d="M8 4.466V.534a.25.25 0 0 0-.41-.192L5.23 2.308a.25.25 0 0 0 0 .384l2.36 1.966A.25.25 0 0 0 8 4.466"></path>
</svg>Повторить отправку</button>
                    `)
                    .on('click', function () {
                        handler.sendCodeAgain()
                    })
                    .appendTo('#code_more_try');

                return;
            } else {
                $('#code_more_try').children('span').text(`${sec} секунд до повторной отправки смс.`)
            }
            sec -= 1;
        }, 1000);
    },
    Handle: function(params) {
        this.showError(params.errorMessage, params.elemShouldShake);
        this.resetCode();
        this.createTimer(params.resetDelayTimeSec);
    }
}

export {WrongCodeHandler, Params}