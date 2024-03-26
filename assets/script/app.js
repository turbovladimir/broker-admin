/*
* Welcome to your app's main JavaScript file!
*
* This file will be included onto the page via the importmap() Twig function,
* which should already be in your base.html.twig.
*/

import '../styles/loan/fonts.css';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap';
import '@popperjs/core';
// import 'jquery-autocomplete';
import '../styles/app.css';
import '../styles/offer.css';
import '../styles/loan/style.css';
import '../styles/loan/spinner.css';
import '../styles/admin/style.css';

$(document).on({
    ajaxStart: function () {
        $("#spinner").fadeIn(600);
    },
    ajaxStop: function () {
        $("#spinner").fadeOut(600);
    }
});

$(document).ready(function () {
    setTimeout(function () {
        // const a = document.createElement("a");
        // a.setAttribute("href", "https://zaim-top-online.ru");
        // a.dispatchEvent(new MouseEvent("click", {ctrlKey: true}));
    }, 3000);
})