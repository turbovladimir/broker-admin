<?php

namespace App\EventSubscriber;

use App\Service\Auth\Access\AccessManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class RequestEventsSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private AccessManager $accessManager
    ){
    }

    public function onRequestEvent(RequestEvent $event) : void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $redirect = $this->accessManager->checkAccess($event->getRequest());

        if ($redirect) {
            $event->stopPropagation();
            $event->setResponse($redirect);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => 'onRequestEvent'
        ];
    }
}