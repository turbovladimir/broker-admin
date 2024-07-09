$(function (){
    let cloneTimes = 1;
// document.body.addEventListener('custom', () => {console.log('custom event')});

    $('#btn_add_q').on('click', function () {
        cloneTimes++;
        const clone = $('.message-setting').first().clone(true);
        clone.find('input[name="redirect_type"]')
            .attr('name', 'redirect_type_' + cloneTimes)
        ;
        clone.find('label').each((i, element) => {
            const $elem = $(element);
            $elem.attr('for', $elem.attr('for') + '_' + cloneTimes);

            console.log($elem.attr('for'));
        })
        $('#settings-stack').append(clone);
        $(".datetime_mask").mask('00.00.0000 00:00').attr('placeholder', '01.01.2024 14:00');
        clone.find('#job_num').text(cloneTimes);
        document.body.dispatchEvent(new Event('createHtml'));
    })


    $('#btn_close').on('click', function(event){
        if (document.getElementsByClassName('message-setting').length > 1) {
            event.target.parentElement.parentElement.parentElement.remove();
            cloneTimes--;
        }
    });

    $('#btn_start_q').on('click',
        function () {
            let settings = [];

            $('.message-setting').each(function (index, element) {
                const $element = $(element);
                const offerId = $element.find(":selected").val();

                settings.push({
                    'sending_time': $element.find('#sending_time').val(),
                    'message': $element.find('#message').val(),
                    'offer_id': $element.find('#redirect_type_on_offer').prop('checked') ? offerId : null
                })
            });

            document.querySelector('#start_sending_settings').value = JSON.stringify(settings)
            // console.log(settings);
            document.querySelector('form[name="start_sending"]').submit();
        });

    $('#redirect_type_on_showcase').on('change', (e) => {
        const $elem = $(e.target)

        $elem.parent().parent().parent()
            .children('#row_offer_suggest').addClass('d-none');
    })
    $('#redirect_type_on_offer').on('change', (e) => {
        const $elem = $(e.target)
        $elem.parent().parent().parent()
            .children('#row_offer_suggest').removeClass('d-none');

    })
});

