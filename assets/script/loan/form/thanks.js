import 'jquery-ui/dist/jquery-ui.min.js'

const ThanksWidget = {
    Show: () => {
        $('#form_main').hide('drop');
        $('#thanks').show('drop');

        setInterval(function () {
            let number = $('#seconds_left');
            let sec = number.text();

            if (sec > 0) {
                number.text(--sec);
            }
        }, 1000);
    },
    Hide: () => {
        $('#thanks').hide('drop');
    }
}

export {ThanksWidget}