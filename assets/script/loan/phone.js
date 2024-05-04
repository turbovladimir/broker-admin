import {PhoneInputHandler} from "./welcome/modal/phone_input.js";
import {CodeInputHandler} from "./welcome/modal/code_input.js";
import {Cambacker} from './welcome/cambaker.js'

$(function () {
    Cambacker($);
    PhoneInputHandler.Handle();
    CodeInputHandler.Handle();
})