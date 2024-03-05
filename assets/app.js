    /*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap';
import '@popperjs/core';
import './styles/app.css';
import './styles/loan/fonts.css';
import './styles/loan/style.css';
import './styles/admin/style.css';

import 'jquery-mask-plugin';
import $ from 'jquery';

    window.$ = window.jQuery = $;
    $(document).ready(function(){
        $(".phone_mask").mask("+7(999)999-99-99").on('click', function () {
            if ($(this).val() === '+7(___)___-__-__') {
                $(this).get(0).setSelectionRange(0, 0);
            }
        });
    })