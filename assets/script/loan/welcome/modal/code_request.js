const CodeRequester = {
    MakeRequest: function (phone, target) {
        $.ajax({
            url: '/phone/verify/request',
            method: 'POST',
            data: {
                'phone': phone
            }

        }).fail(function (data) {
            if (data.responseJSON.error !== undefined) {
                console.log(data.responseJSON.error);

                if ($('#modal-block-error').is(':empty')) {
                    $(`<div class="row row-cols-1 g-3">
                        <div id="code-error-message" class="col"><p class="error-text">${data.responseJSON.error}</p></div>
                        </div>`).appendTo('#modal-block-error');
                    $('#modal-block-error').effect('shake');
                    $('#phone').effect('shake');
                    $('#btn_send_sms').removeAttr('disabled');
                }
            } else {
                alert('Что то пошло не так...');
            }
        })
            .done(function () {
                $(target).trigger('send_sms_success');
            });
    }
}

export {CodeRequester}