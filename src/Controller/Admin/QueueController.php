<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
use App\Enums\QueueStatus;
use App\Entity\SendingSmsJob;
use App\Entity\Sms;
use App\Entity\SmsQueue;
use App\Event\AdminCreateDistributionEvent;
use App\Form\DTO\StartSendingRequest;
use App\Form\SmsQueueType;
use App\Form\StartSendingType;
use App\Repository\SmsQueueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route('/admin/sms', name: 'admin_sms_')]
class QueueController extends AbstractController
{

    #[Route('/qs', name: 'queues_list')]
    public function queuesList(SmsQueueRepository $smsQueueRepository) : Response
    {
        return $this->render('@admin/sms/queue/list.html.twig', [
            'queues' => $smsQueueRepository->getList()
        ]);
    }

    #[Route('/q/create', name: 'queue_create')]
    public function queueCreate(Request $request, EventDispatcherInterface $dispatcher) : Response
    {
        $queue = new SmsQueue();
        $form = $this->createForm(SmsQueueType::class, $queue);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dispatcher->dispatch(new AdminCreateDistributionEvent($request, $queue));

            return $this->redirectToRoute('admin_sms_queues_list');
        }

        return $this->render('@admin/sms/queue/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/q/start/{queue}', name: 'queue_start')]
    public function queueStart(SmsQueue $queue, Request $request, EntityManagerInterface $entityManager) : Response
    {
        $dto = new StartSendingRequest();
        $form = $this->createForm(StartSendingType::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $settings = $dto->getSettings();

            foreach ($settings as $setting) {
                //todo add redirect type
                foreach ($queue->getContacts()->toArray() as $contact) {
                    $time = \DateTime::createFromFormat('d.m.Y H:i', $setting['sending_time']);
                    $entityManager->persist($contact->addSendingSmsJob((new SendingSmsJob($time, $queue))
                        ->setSms($this->createSms($setting['message'], $contact, $queue)))
                    );
                }
            }

            $entityManager->persist($queue->setStatus(QueueStatus::InProcess));
            $entityManager->flush();

            return $this->redirectToRoute('admin_sms_queues_list');
        }

        return $this->render('@admin/sms/queue/start.html.twig', [
            'queue' => $queue,
            'form' => $form->createView()
        ]);
    }

    private function createSms(string $text, Contact $contact, SmsQueue $queue) : Sms
    {
        $url = $this->generateUrl('redirect', ['contact' => $contact->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
        $text = str_replace('#url#', $url, $text);

        return (new Sms($text))->setSmsQueue($queue);
    }

}