<?php

namespace App\EventSubscriber;

use App\Event\UploadFileFinishEvent;
use App\Service\CommandExecuter;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UploadFileSubscriber implements EventSubscriberInterface
{

    public function __construct(private CommandExecuter $commandExecuter)
    {
    }


    public function onUploadFileFinishEvent(UploadFileFinishEvent $event): void
    {
        $this->commandExecuter->executeCommand(new ArrayInput([
                'command' => 'asset-map:compile',
                // (optional) define the value of command arguments
//                'fooArgument' => 'barValue',
                // (optional) pass options to the command
//                '--bar' => 'fooValue',
                // (optional) pass options without value
//                '--baz' => true,
            ])
        );
    }

    public static function getSubscribedEvents(): array
    {
        return [
            UploadFileFinishEvent::class => 'onUploadFileFinishEvent',
        ];
    }
}
