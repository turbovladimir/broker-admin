$(function (){
    let cloneTimes = 1;
// document.body.addEventListener('custom', () => {console.log('custom event')});

    $('#btn_add_q').on('click', function () {
        const clone = $('.message-setting').last().clone(true);
        $('#settings-stack').append(clone);
        $(".datetime_mask").mask('00.00.0000 00:00').attr('placeholder', '01.01.2024 14:00');
        const jobNum =  clone.find('#job_num');
        let n = jobNum.text();
        jobNum.text(++n);
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

            document.querySelectorAll('.message-setting').forEach((settingHtml) => {
                settings.push({
                    'sending_time': settingHtml.querySelector('#sending_time').value,
                    'message': settingHtml.querySelector('#message').value
                })
            });

            document.querySelector('#start_sending_settings').value = JSON.stringify(settings)
            document.querySelector('form[name="start_sending"]').submit();
        });
});

