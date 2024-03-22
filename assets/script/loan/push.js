$(document).ready(function () {
    showPush(5000);

    function showPush(duration) {

        setTimeout(function (){
            if ($(".toast").length > 0) {
                $(".toast").show();
            }

            setTimeout(function () {
                $(".toast").hide();
            }, duration);
        }, 3000);
    }
})