console.log('redirect');
console.time("register user");
$.ajax({
    async: false,
    url: '/api/user/reg',
    method: 'GET',
})
    .fail(() => {
        alert('Упс, что то пошло не так.')
    })
    .done(function (response) {
        console.timeEnd('register user');
         location.replace(response.data.redirect_url);
    })