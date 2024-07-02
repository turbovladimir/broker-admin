<?php

namespace App\Controller;

enum Session : string
{
    case RegistrationType = 'reg_type';
    case Contact = 'contact';
    case PhoneVerified = 'verified';
    case FormPass = 'form_pass';
    case UserRegistered = 'user_registered';
    case ExcludeOfferIds = 'exclude_offer_ids';
}