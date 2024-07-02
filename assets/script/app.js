/*
* Welcome to your app's main JavaScript file!
*
* This file will be included onto the page via the importmap() Twig function,
* which should already be in your base.html.twig.
*/
import '../styles/loan/fonts.css'
import '../styles/app.css';
import '../styles/offer.css';
import '../styles/loan/style.css';
import '../styles/loan/spinner.css';
import '../styles/loan/push.css';
import '../styles/admin/style.css';


import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap-icons/font/bootstrap-icons.min.css';
import 'bootstrap';
import '@popperjs/core';
import 'jquery-mask-plugin';


$(document).on({
    ajaxStart: function () {
        $("#spinner").fadeIn(600);
    },
    ajaxStop: function () {
        $("#spinner").fadeOut(600);
    }
});


$(function () {
    App.init();
})

const App = {
    init: function () {
        document.body.addEventListener('createHtml', function () {
            Mask($);
        })

        document.body.dispatchEvent(new Event('createHtml'));

        if (window.env === 'prod') {
            (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
                m[i].l=1*new Date();
                for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
                k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
            (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

            ym(window.counter_id, "init", {
                clickmap:true,
                trackLinks:true,
                accurateTrackBounce:true
            });

            $('.btn-get-loan').on('click', function () {
                const amount = $('#range_amount').val();
                ym(window.counter_id, 'reachGoal', 'btn-get-loan', {loan_amount: amount});
            })
            $('#lnk-vk-bot').on('click', function () {
                ym(window.counter_id, 'reachGoal', 'lnk-vk-bot');
            })
            $('#btn_send_sms').on('click', function () {
                ym(window.counter_id, 'reachGoal', 'btn_send_sms');
            })
            $('.code').on('input_code_success', function () {
                ym(window.counter_id, 'reachGoal', 'input_code_success');
            })
            $('.code').on('input_code_fail', function () {
                ym(window.counter_id, 'reachGoal', 'input_code_fail');
            })
            $('#btn_from_sbm').on('frm_step_success', function () {
                ym(window.counter_id, 'reachGoal', 'frm_step_success');
            })
            $('.lnk-offer-rdr').on('click', function () {
                ym(window.counter_id, 'reachGoal', 'lnk-offer-rdr');
            })
        }
    }
}

const Mask = ($) => {
    console.log('init masks');
    $(".phone_mask").mask("+7(999)999-99-99").on('click', function () {
        if ($(this).val() === '+7(___)___-__-__') {
            $(this).get(0).setSelectionRange(0, 0);
        }
    });
    $("#loan_request_passportSeries").mask('0000');
    $("#loan_request_passportNumber").mask('000000');
    $("#loan_request_departmentCode").mask('000-000');
    $(".date_mask").mask('00.00.0000');
    $(".datetime_mask").mask('00.00.0000 00:00').attr('placeholder', '01.01.2024 14:00');
    $('.email_mask').mask("A", {
        translation: {
            "A": {pattern: /[\w@\-.+]/, recursive: true}
        }
    });
}