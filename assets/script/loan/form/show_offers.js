import 'jquery-ui/dist/jquery-ui.min.js'

const OfferWidget = {
    Show: function (div_id) {
        const offerList = this.fetchOfferList();

        if (!offerList) {
            return;
        }

        $(offerList).appendTo($(`#${div_id}`));
        let showAsyncElems =  $('.show_async');

        showAsyncElems.hide();
        $('.offer_wrap').show('drop');

        let time = 500;

        showAsyncElems.each(function() {
            const elem = $(this);

            setTimeout( function(){
                elem.show('drop')
                    .removeClass('show_async')
                ;

            }, time);
            time += 500;
        });
    },
    fetchOfferList: () => {
        let offer_list = null;

        $.ajax({
            async: false,
            url: '/loan/offer/show',
            method: 'GET',
        })
            .done(function (response) {
                offer_list = response.offer_list;
            })

        return offer_list;
    }
}

export {OfferWidget}