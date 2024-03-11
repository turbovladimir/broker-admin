    /*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */

import './styles/loan/fonts.css';

import 'jquery-mask-plugin';
import $ from 'jquery';
window.$ = window.jQuery = $;
    console.log('main');

import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap';
import '@popperjs/core';
import './styles/app.css';
import './styles/loan/style.css';
import './styles/admin/style.css';


    $(document).ready(function(){
        $(".phone_mask").mask("+7(999)999-99-99").on('click', function () {
            if ($(this).val() === '+7(___)___-__-__') {
                $(this).get(0).setSelectionRange(0, 0);
            }
        });

        $('#btn_send_sms').click(function () {
            $('#phone_verify').hide();
            $('#form_main').slideDown('fast');
        });
        $('#btn_continue').click(function () {
            $('#form_step_personal_data').hide();
            $('#form_step_passport').slideDown('fast');
            $('#step2').text( 'Шаг 3' );
        });
        $('#btn_from_sbm').click(function () {
            $('#form_main').hide();
            $('#thanks').slideDown('fast');

            setInterval(function () {
                let sec = $('#seconds_left').text();

                if (sec > 0) {
                    $('#seconds_left').text(--sec);
                }
            }, 1000);
        });
    })