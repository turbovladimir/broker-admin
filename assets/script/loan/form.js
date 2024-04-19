import {StepHandler} from "./form/step.js";
import 'jquery-ui/dist/jquery-ui.min.js'
import {ThanksWidget} from "./form/thanks.js";
import {OfferWidget} from "./form/show_offers.js";

/**
 * form behavior here
 */

$(function () {
    const btnContinueId = 'btn_continue',
        step1Id = 'form_step_personal_data',
        btnSbmId = 'btn_from_sbm',
        step2Id = 'form_step_passport'
    ;

    StepHandler.Handle(btnContinueId, step1Id, () => {
        $(`#${step1Id}`).hide('drop');
        $(`#${step2Id}`).show('drop');
        $('#btn_step').children('span').text('2');

        StepHandler.Handle(btnSbmId, step2Id, () => {
            $(document).off('ajaxStart');
            $(document).off('ajaxStop');

            const $form = $('form[name="loan_request"]');

            $.ajax({
                url: $form.attr('action'),
                method: 'POST',
                data: $form.serialize(),
                beforeSend: () => ThanksWidget.Show()
            })
                .fail(function( data ) {
                    alert(data.error);
                })
                .done(function () {
                    ThanksWidget.Hide();
                    OfferWidget.Show( 'offer_list')
                })
            ;
        });
    });
})