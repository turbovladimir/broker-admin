<?php

namespace App\Service\Sms;

use App\Entity\DistributionJob;
use App\Entity\SendingSmsJob;
use App\Entity\Sms;
use App\Enums\QueueStatus;
use App\Enums\RedirectType;
use App\Enums\SendingJobStatus;
use App\Service\Integration\LinkShortener\LinkShortenerInterface;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Exception\ClientException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Scheduler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UrlGeneratorInterface $urlGenerator,
        private LinkShortenerInterface $linkShortener,
        private LoggerInterface $smsLogger,
        private string $domain
    ){}

    public function makeDistribution(DistributionJob $distributionJob) : void
    {
        $settings = $distributionJob->getSettings();
        $queue = $distributionJob->getQueue();

        foreach ($settings as $setting) {
            $offerId =  !is_null($setting['offer_id']) ?  (int)$setting['offer_id'] : null;
            $redirectType = $offerId ? RedirectType::ON_OFFER : RedirectType::ON_SHOWCASE;

            foreach ($queue->getContacts()->toArray() as $contact) {
                $time = \DateTime::createFromFormat('d.m.Y H:i', $setting['sending_time']);
                $sendingJob = new SendingSmsJob($time, $queue, $contact, $redirectType);
                $this->createSms($sendingJob, $setting['message'], $offerId);
                $this->entityManager->persist($sendingJob);
            }
        }

        $this->entityManager->persist($queue->setStatus(QueueStatus::InProcess));
        $this->entityManager->flush();
    }

    private function createSms(SendingSmsJob $sendingSmsJob, string $text, ?int $offerId) : void
    {
        $contact = $sendingSmsJob->getContact();
        $queue = $sendingSmsJob->getSmsQueue();
        $path = $this->urlGenerator->generate('redirect_view');
        $url = 'https://' . $this->domain . $path . sprintf('?c=%s&o=%d', $contact->getContactId(), $offerId);

        try {
            $time = microtime(true);
            $short = $this->linkShortener->shorting($url);
        } catch (ClientException $exception) {

        } finally {
            $this->smsLogger->info('Shorting link', [
                'contact_id' => $contact->getContactId(),
                'origin' => $url,
                'short' => $short ?? null,
                'spent_milisecs' => round(microtime(true) - $time, 5)
            ]);

            $text = str_replace('#url#', $short ?? $url, $text);
            $sms = (new Sms($text))->setSmsQueue($queue);
        }


        $sendingSmsJob
            ->setSms($sms)
            ->setStatus(isset($exception) ? SendingJobStatus::Error : SendingJobStatus::InQueue)
            ->setErrorText(isset($exception) ? $exception->getMessage() : null)
        ;
    }
}