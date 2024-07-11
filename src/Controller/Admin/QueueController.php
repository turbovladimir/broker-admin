<?php

namespace App\Controller\Admin;

use App\Entity\DistributionJob;
use App\Entity\SmsQueue;
use App\Enums\QueueStatus;
use App\Event\AdminCreateDistributionEvent;
use App\Form\DTO\StartSendingRequest;
use App\Form\SmsQueueType;
use App\Form\StartSendingType;
use App\Repository\OfferRepository;
use App\Repository\SmsQueueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

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
    public function queueStart(
        SmsQueue $queue,
        Request $request,
        EntityManagerInterface $entityManager,
        OfferRepository $offerRepository
    ) : Response
    {
        $dto = new StartSendingRequest($queue);
        $form = $this->createForm(StartSendingType::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dist = new DistributionJob();
            $dist
                ->setQueue($queue)
                ->setSettings($dto->getSettings())
                ->setAddedAt(new \DateTime());
            $entityManager->persist($queue->setStatus(QueueStatus::Adjusted));
            $entityManager->persist($dist);
            $entityManager->flush();

            return $this->redirectToRoute('admin_sms_queues_list');
        }

        $offers = $offerRepository->findOffers([], null);

        return $this->render('@admin/sms/queue/start.html.twig', [
            'queue' => $queue,
            'form' => $form->createView(),
            'offers' => $offers
        ]);
    }

}