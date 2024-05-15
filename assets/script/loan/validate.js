$(function () {
    $('.birth_validate').on('focusout', function () {
        const date = new Date();
        const birtMin = subtractYears(date, 18);
        const birtMax = subtractYears(date, 60);
        let inputDate = $(this).val();
        const DateParts = inputDate.split('.');

        const DateBirth = new Date(inputDate);
        const btn = $('#btn_continue');

        if (DateParts[0] > 31 || DateParts[1] > 12 || DateParts[2] > date.getFullYear()) {
            alert('Введите корректную дату рождения');
            btn.prop('disabled', true)
                .addClass('opacity-50')
            ;

            return;
        }

        if (DateBirth > birtMin || DateBirth < birtMax) {
            alert('К сожалению, из-за возрастных ограничений, мы не можем принять вашу заявку.');
            btn.prop('disabled', true);
        }

        btn.removeAttr('disabled').removeClass('opacity-50');
    });
})

function addError(input, message) {
    if (input.hasClass('error_input')) {
        return;
    }

    input.addClass('error_input');
    const label = $('label[for="' + input.attr('id') + '"]');
    label.addClass('error-text');
    label.text(message);

    input.on('focus', function () {
        input.removeClass('error_input');
        label.removeClass('error-text');
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
    subDate.setFullYear(subDate.getFullYear() - years);

    return subDate;
}

export {addError, addGood, isValid};