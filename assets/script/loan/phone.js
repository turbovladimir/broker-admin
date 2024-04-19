import {PhoneInputHandler} from "./welcome/modal/phone_input.js";
import {CodeInputHandler} from "./welcome/modal/code_input.js";

$(function () {
    PhoneInputHandler.Handle();
    CodeInputHandler.Handle();
})