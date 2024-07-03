<?php

namespace App\Service\Auth\Access;

use App\Controller\Session;
use Symfony\Component\HttpFoundation\Request;

final class UserActionsChecker
{
    public function isPhoneVerified(Request $request) : bool
    {
        return $request->getSession()->get(Session::PhoneVerified->value, false);
    }

    public function isUserRegistered(Request $request) : bool
    {
        $s = $request->getSession();
        $type = $s->get(Session::RegistrationType->value);

        return match ($type) {
            RegistrationType::Short->value => $s->has(Session::Contact->value) && $s->has(Session::ExcludeOfferIds->value),
            RegistrationType::Long->value =>
                $s->has(Session::Contact->value) &&
                $s->has(Session::PhoneVerified->value) &&
                $s->has(Session::FormPass->value) &&
                $s->has(Session::ExcludeOfferIds->value),
            default => false
        };
    }
}