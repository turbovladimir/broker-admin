<?php

namespace App\Command;

use App\Enums\QueueStatus;
use App\Repository\DistributionJobRepository;
use App\Repository\SendingSmsJobRepository;
use App\Repository\SmsQueueRepository;
use App\Service\Sms\Scheduler;
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
        private Sender $sender,
        private Scheduler $scheduler
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $this->makeDistribution();
        $this->sendJobs($input, $output);

        return Command::SUCCESS;
    }

    private function makeDistribution()
    {
        $qs = $this->smsQueueRepository->findBy(['status' => QueueStatus::Adjusted]);

        foreach ($qs as $q) {
            $this->scheduler->makeDistribution($q->getDistributionJob());
        }
    }

    private function sendJobs(InputInterface $input, OutputInterface $output) : void
    {
        $i = 0;

        while($jobs = $this->sendingSmsJobRepository->findEnqueuedJobs(500)) {
            $this->sender->massSending($jobs);
            $this->smsQueueRepository->actualizeStatuses();
            $i++;
        }

        if (!$i) {
            $output->writeln('Queue is empty. Skip...');
        }
    }
}