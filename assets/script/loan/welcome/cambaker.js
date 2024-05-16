const Cambacker = ($) => {
    if (window.env !== 'prod') {
        return;
    }

    const has_popup_query = new URL(window.location.href).searchParams.has('popup_show');
    const btn_get_loan = $('.btn-get-loan');

    if (btn_get_loan.length > 0 && has_popup_query) {
        btn_get_loan.first().trigger('click');
    }

    btn_get_loan.on('click' , function (e) {

        if (!has_popup_query) {
            e.preventDefault();

            let url = new URL(window.location.href);
            url.searchParams.set('popup_show', 1);

            const cambacker =
                $(`<a id="camebacker" href="${url}" target="_blank"></a>`);
            cambacker.appendTo(document.body);
            cambacker[0].click();

            setTimeout(() => window.location.replace('§/'), 1000);
        }
    });
}

export {Cambacker}