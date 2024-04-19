const Validator = {
    stepId: null,
    inputs: [],
    errorInputClass: 'error_input',
    errorTextClass: 'error-text',
    Init: function (stepId) {
        this.stepId = stepId;
        this.inputs = $(`#${this.stepId}`).find('input');
        this.inputs.on({
            focusout: e => this.validate($(e.target)),
            focusin: e => this.removeError($(e.target)),
        });
    },
    IsValid: function () {
        this.inputs.each((i, input) => {
            this.validate($(input));
        });

        return this.inputs.hasClass(this.errorInputClass);
    },
    removeError: function (input) {
        if (input.hasClass(this.errorInputClass)) {
            input.removeClass(this.errorInputClass);
        }
    },
    validate: function (input) {
        const message = 'Заполните обязательные поля';

        if (!input.hasClass(this.errorInputClass) && input.val() === '') {
            this.addError(input, message);

            return;
        }

        if (input.hasClass('birth_validate')) {
            const date = new Date();
            const birtMin = this.subtractYears(date, 18);
            const birtMax = this.subtractYears(date, 60);
            let inputDate = input.val();
            const DateParts = inputDate.split('.');

            const DateBirth = new Date(inputDate);

            if (DateParts[0] > 31 || DateParts[1] > 12 || DateParts[2] > date.getFullYear()) {
                this.addError(input, 'Введите корректную дату рождения');

                return;
            }

            if (DateBirth > birtMin || DateBirth < birtMax) {
                this.addError(input, 'К сожалению, из-за возрастных ограничений, мы не можем принять вашу заявку.');

                return;
            }


        }
    },
    subtractYears: (date, years) => {
        const subDate = new Date(date.getTime());
        subDate.setFullYear(subDate.getFullYear() - years);

        return subDate;
    },
    addError: function (input, message) {
        console.log('addError');
        input.addClass(this.errorInputClass);
        const errorBlock = $('#block_error');
        let hasErrorMessage = false;
        errorBlock
            .children('div')
            .children('p')
            .each((i, elem) => {
                if (!hasErrorMessage) {
                    hasErrorMessage = $(elem).text() === message;
                }
            })

        if (hasErrorMessage) {
            return;
        }

        $(`
        <div class="col-md-auto">
            <p class="${this.errorTextClass}">${message}</p>
        </div>
        `).appendTo(errorBlock);
    }
}

export {Validator}