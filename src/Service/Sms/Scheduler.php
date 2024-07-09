<?php

namespace App\Service\Sms;

use App\Entity\Contact;
use App\Entity\SendingSmsJob;
use App\Entity\Sms;
use App\Entity\SmsQueue;
use App\Enums\QueueStatus;
use App\Enums\RedirectType;
use App\Form\DTO\StartSendingRequest;
use App\Service\Integration\LinkShortener\LinkShortenerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Scheduler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UrlGeneratorInterface $urlGenerator,
        private LinkShortenerInterface $linkShortener,
        private string $domain
    ){}

    public function makeDistribution(StartSendingRequest $request) : void
    {
        $settings = $request->getSettings();
        $queue = $request->queue;

        foreach ($settings as $setting) {
            $offerId =  !is_null($setting['offer_id']) ?  (int)$setting['offer_id'] : null;
            $redirectType = $offerId ? RedirectType::ON_OFFER : RedirectType::ON_SHOWCASE;

            //todo add redirect type
            foreach ($queue->getContacts()->toArray() as $contact) {
                $time = \DateTime::createFromFormat('d.m.Y H:i', $setting['sending_time']);
                $this->entityManager->persist($contact->addSendingSmsJob((new SendingSmsJob($time, $queue, $redirectType))
                    ->setSms($this->createSms($setting['message'], $contact, $queue, $offerId)))
                );
            }
        }

        $this->entityManager->persist($queue->setStatus(QueueStatus::InProcess));
        $this->entityManager->flush();
    }

    private function createSms(string $text, Contact $contact, SmsQueue $queue, ?int $offerId) : Sms
    {
        $path = $this->urlGenerator->generate('redirect_view');
        $url = 'https://' . $this->domain . $path . sprintf('?c=%d&o=%d', $contact->getContactId(), $offerId);
        $text = str_replace('#url#', $this->linkShortener->shorting($url), $text);

        return (new Sms($text))->setSmsQueue($queue);
    }
}