import 'jquery-ui/dist/jquery-ui.min.js'
import {ThanksWidget} from "./form/thanks.js";
import {OfferWidget} from "./form/show_offers.js";
import {Validator} from "./form/validate.js";

/**
 * form behavior here
 */

$(function () {
    $(document).off('ajaxStart');
    $(document).off('ajaxStop');
    Validator.Init('form_step_1')
    const btn =  $('#btn_from_sbm');

    btn.on({
        click: function (e) {
            e.preventDefault();
            if (!Validator.hasErrors()) {
                $(e.target).trigger('frm_step_success');

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
            }
        }
    });
})