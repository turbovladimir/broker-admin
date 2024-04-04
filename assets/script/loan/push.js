$(function () {
    $.ajax({
        url: '/push/get',
        method: 'POST',
        data: {
            'path' : window.location.pathname
        },
    })
        .done(function (response) {
            if (response.push.target !== undefined) {
                showPush(response.push);
            }
        })

    function showPush(push) {
        setTimeout(function (){
            if ($(".toast").length > 0) {
                $('#push-text').append(push.text);
                $('#push-link').attr('href', push.target);
                $('.toast').slideDown('slow');
                $('.toast').promise().done( function () {
                    console.log('play time');
                    const audio = document.getElementById('push_audio');
                    audio.muted = false;
                    audio.play();
                })
            }
        }, push.show_delay * 1000);
    }
})