<?php

namespace App\EventSubscriber;

use App\Service\Auth\Access\AccessManager;
use App\Service\Auth\Access\Exception\UserAccessExceededException;
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

    public function onRequestEvent(RequestEvent $event)
    {
        if (!$event->isMainRequest()) {
            return;
        }

        try {
            $this->accessManager->checkAccess($event->getRequest());
        } catch (UserAccessExceededException $exception) {
            $event->stopPropagation();
            $event->setResponse(new RedirectResponse('https://zaim-top-online.ru'));
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => 'onRequestEvent'
        ];
    }
}