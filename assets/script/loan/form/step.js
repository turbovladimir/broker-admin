import 'jquery-ui/dist/jquery-ui.min.js'
import {Validator} from "./validate.js";

const StepHandler = {
    btn: null,
    stepId: null,
    goNext: null,
    Handle: function (buttonId, stepId, goNext) {
        this.stepId = stepId;
        this.goNext = goNext;

        Validator.Init(stepId);
        const btn = $(`#${buttonId}`);
        btn.on({
            click: e => this.onClickButton(e),
        });
    },
    onClickButton: function (e) {
        e.preventDefault();

        if (!Validator.IsValid()) {
            this.goNext();
        }
    }
}

export {StepHandler}