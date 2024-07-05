<?php

namespace App\Service\Sms;

use App\Entity\Contact;
use App\Entity\SendingSmsJob;
use App\Entity\Sms;
use App\Entity\SmsQueue;
use App\Enums\QueueStatus;
use App\Form\DTO\StartSendingRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Scheduler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UrlGeneratorInterface $urlGenerator,
        private string $domain
    ){}

    public function makeDistribution(StartSendingRequest $request) : void
    {
        $settings = $request->getSettings();
        $queue = $request->queue;

        foreach ($settings as $setting) {
            //todo add redirect type
            foreach ($queue->getContacts()->toArray() as $contact) {
                $time = \DateTime::createFromFormat('d.m.Y H:i', $setting['sending_time']);
                $this->entityManager->persist($contact->addSendingSmsJob((new SendingSmsJob($time, $queue))
                    ->setSms($this->createSms($setting['message'], $contact, $queue)))
                );
            }
        }

        $this->entityManager->persist($queue->setStatus(QueueStatus::InProcess));
        $this->entityManager->flush();
    }

    private function createSms(string $text, Contact $contact, SmsQueue $queue) : Sms
    {
        $path = $this->urlGenerator->generate('redirect', ['contact' => $contact->getId()]);
        $url = 'https://' . $this->domain . $path;
        $text = str_replace('#url#', $url, $text);

        return (new Sms($text))->setSmsQueue($queue);
    }
}