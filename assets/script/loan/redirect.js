setTimeout($ => {
    $.ajax({
        async: false,
        url: document.URL,
        method: 'POST',
    })
        .fail(() => {
            alert('Упс, что то пошло не так.')
        })
        .done(function (response) {
            console.timeEnd('register user');
            location.replace(response.data.redirect_url);
        })
}, 3000)