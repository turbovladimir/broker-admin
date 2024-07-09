<?php

namespace App\Service\Auth\Access;

use App\Controller\Session;
use App\Enums\RegistrationType;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;

final class UserActionsChecker
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    public function isPhoneVerified(Request $request) : bool
    {
        return $request->getSession()->get(Session::PhoneVerified->value, false);
    }

    public function isUserRegistered(Request $request) : bool
    {
        $s = $request->getSession();
        $type = $s->get(Session::RegistrationType->value);
        $hasContactHash = $s->has(Session::ContactHash->value);
        $isExistFilterOffers = $s->has(Session::ExcludeOfferIds->value);
        $isPhoneVerified = $s->has(Session::PhoneVerified->value);
        $formPassed = $s->has(Session::FormPass->value);

        $this->logger->debug('Check is user registered?', [
            'reg_type' => $type,
            'has_contact_hash' => $hasContactHash,
            'is_exist_offer_filter' => $isExistFilterOffers,
            'is_phone_verified' => $isPhoneVerified,
            'form_passed' => $formPassed
        ]);

        return match ($type) {
            RegistrationType::Short->value => $hasContactHash && $isExistFilterOffers,
            RegistrationType::Long->value =>
                $hasContactHash && $isPhoneVerified && $formPassed && $isExistFilterOffers,
            default => false
        };
    }
}