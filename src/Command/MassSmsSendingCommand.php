<?php

namespace App\Command;

use App\Repository\SendingSmsJobRepository;
use App\Repository\SmsQueueRepository;
use App\Service\Sms\Sender;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:sms-send',
    description: 'Mass sms sending',
)]
class MassSmsSendingCommand extends Command
{
    public function __construct(
        private SendingSmsJobRepository $sendingSmsJobRepository,
        private SmsQueueRepository $smsQueueRepository,
        private Sender $sender
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $jobs = $this->sendingSmsJobRepository->findEnqueuedJobs();

        if (empty($jobs)) {
            $output->writeln('Queue is empty.');

            return Command::SUCCESS;
        }

        $this->sender->massSending($jobs);
        $this->smsQueueRepository->actualizeStatuses();

        return Command::SUCCESS;
    }
}