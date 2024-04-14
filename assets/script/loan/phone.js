import 'bootstrap-icons/font/bootstrap-icons.min.css'
import {PhoneInputHandler} from "./modal/phone_input.js";
import {CodeInputHandler} from "./modal/code_input.js";

$(function () {
    PhoneInputHandler.Handle();
    CodeInputHandler.Handle();
})