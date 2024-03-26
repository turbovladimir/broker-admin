$(function () {
    ShowOffers();
    function ShowOffers() {
        $('.offer_wrap').fadeIn('fast');

        $('.offer_wrap').children().each(function (i) {
            $(this)
                .fadeIn((i + 1) * 1000);
        })
    }
})