$(document).ready(function () {
    $('#loan_request_code').on('focusout', function () {
        const elem = $(this);
        const code = elem.val();

        $.get("/loan/verify/" + code)
            .done(function () {
                addGood(elem);
                elem.prop('disabled', true);
            })
            .fail(function () {
                addError(elem, "Недействительный проверочный код.");
            })
        ;
    });
    $('.birth_validate').on('focusout', function () {
        const date = new Date();
        const birtMin = subtractYears(date, 18);
        const birtMax = subtractYears(date, 60);
        let inputDate = $(this).val();
        inputDate = inputDate.replace(/(\d+)\/(\d+)\/(\d+)/g, "$3-$2-$1")
        const DateBirth = new Date(inputDate)

        if (date <= DateBirth) {
            alert('Введите корректную дату рождения');
            $('#btn_continue').prop('disabled', true);

            return;
        }

        if (DateBirth > birtMin || DateBirth < birtMax) {
            alert('К сожалению, из-за возрастных ограничений, мы не можем принять вашу заявку.');
            $('#btn_continue').prop('disabled', true);
        }
    });
})

function addError(input, message) {
    if (input.hasClass('error_input')) {
        return;
    }

    input.addClass('error_input');
    const label = $('label[for="' + input.attr('id') + '"]');
    label.addClass('error');
    label.text(message);

    input.on('focus', function () {
        input.removeClass('error_input');
        label.removeClass('error');
        label.text(input.attr('placeholder'));
    });
}

function addGood(input) {
    if (input.hasClass('good_input')) {
        return;
    }

    input.addClass('good_input');
}

function isValid(id, pat) {
    var value = $(id).val();
    var pattern = new RegExp("^" + pat + "", "i");
    if (pattern.test(value)) {
        return true;
    } else {
        return false;
    }
}

function subtractYears(date, years) {
    const subDate = new Date(date.getTime());
    console.log(subDate);
    subDate.setFullYear(subDate.getFullYear() - years);

    return subDate;
}

export {addError, addGood, isValid};