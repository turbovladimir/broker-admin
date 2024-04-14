const CodeRequester = {
    MakeRequest: function (phone) {
        $.ajax({
            url: '/phone/verify/request',
            method: 'POST',
            data: {
                'phone': phone
            }
        })
    }
}

export {CodeRequester}