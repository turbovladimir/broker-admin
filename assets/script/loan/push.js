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
        console.log('push delay');

        setTimeout(function (){
            if ($(".toast").length > 0) {
                console.log('push show');
                $('#push-text').append(push.text);
                $('#push-link').attr('href', push.target);
                $('.toast').slideDown('slow');
            }
        }, push.show_delay * 1000);
    }
})