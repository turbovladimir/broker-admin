<?php

namespace App\EventSubscriber;

use App\Controller\Loan\OfferController;
use App\Event\UserRequestVerifyPhoneEvent;
use App\Service\Auth\Access\AccessManager;
use App\Service\Auth\PhoneVerifier;
use App\Service\Checker\Service as Checker;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserVerifyPhoneSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private PhoneVerifier $phoneVerifier,
        private Checker $checker,
        private AccessManager $accessManager
    ){
    }


    public function onUserRequestVerifyPhoneEvent(UserRequestVerifyPhoneEvent $event): void
    {
        $request = $event->getRequest();
        $session = $request->getSession();
        $this->phoneVerifier->sendCode($session->getId(), $event->getPhone());
        $this->accessManager->incLimit($request);
        $result = $this->checker->checkPhone($event->getPhone());
        $session->set(OfferController::SESSION_EXCLUDE_OFFER_IDS, $result->getExcludeOfferIds());
    }

    public static function getSubscribedEvents(): array
    {
        return [
            UserRequestVerifyPhoneEvent::class => 'onUserRequestVerifyPhoneEvent',
        ];
    }
}
