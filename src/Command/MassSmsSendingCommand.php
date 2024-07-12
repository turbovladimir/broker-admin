<?php

namespace App\Command;

use App\Enums\QueueStatus;
use App\Enums\SendingJobStatus;
use App\Repository\SendingSmsJobRepository;
use App\Repository\SmsQueueRepository;
use App\Service\Sms\Scheduler;
use App\Service\Sms\SenderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Stopwatch\Stopwatch;

#[AsCommand(
    name: 'app:sms-send',
    description: 'Mass sms sending',
)]
class MassSmsSendingCommand extends Command
{
    public function __construct(
        private Stopwatch $stopwatch,
        private SendingSmsJobRepository $sendingSmsJobRepository,
        private SmsQueueRepository $smsQueueRepository,
        private SenderInterface $sender,
        private EntityManagerInterface $entityManager,
        private LoggerInterface $smsLogger,
        private Scheduler $scheduler
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $output->writeln('Start monitoring');
        $this->stopwatch->start('command_execute');
        $this->makeDistribution();
        $this->sendJobs($input, $output);
        $this->smsQueueRepository->actualizeStatuses();
        $output->writeln($this->stopwatch->stop('command_execute'));

        return Command::SUCCESS;
    }

    private function makeDistribution() : void
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
            foreach ($jobs as $job) {
                $phone = $job->getContact()->getPhone();
                $message = $job->getSms()->getMessage();
                $this->smsLogger->info('Lets do another great job...', ['job' => [
                    'id' => $job->getId(),
                    'phone' => $phone,
                    'message' => $message
                ]]);

                try {
                    $this->sender->send([$phone], $message);
                } catch (\Throwable $exception) {
                    $this->smsLogger->warning($exception->getMessage());
                    $this->entityManager->persist(
                        $job->setStatus(SendingJobStatus::Error)
                            ->setErrorText($exception->getMessage())
                    );

                    continue;
                }

                $this->smsLogger->info('Sir, job is done!');
                $this->entityManager->persist($job->setStatus(SendingJobStatus::Sent));
            }

            $this->entityManager->flush();
            $i++;
        }

        if (!$i) {
            $output->writeln('Queue is empty. Skip...');
        }
    }
}