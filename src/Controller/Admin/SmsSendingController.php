<?php

namespace App\Controller\Admin;

use App\Entity\SmsQueue;
use App\Form\SmsQueueType;
use App\Repository\SmsQueueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/sms', name: 'admin_sms_')]
class SmsSendingController extends AbstractController
{

    #[Route('/qs', name: 'queues_list')]
    public function queuesList(SmsQueueRepository $smsQueueRepository) : Response
    {
        return $this->render('@admin/sms/queue/list.html.twig', [
            'queues' => $smsQueueRepository->findAll()
        ]);
    }

    #[Route('/q/create', name: 'queue_create')]
    public function queueCreate(Request $request, EntityManagerInterface $entityManager) : Response
    {
        $queue = new SmsQueue();
        $form = $this->createForm(SmsQueueType::class, $queue);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($queue);
            $entityManager->flush();

            return $this->redirectToRoute('admin_sms_queues_list');
        }

        return $this->render('@admin/sms/queue/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/q/{queue}', name: 'queue_details')]
    public function queueDetails(SmsQueue $queue) : Response
    {
        return $this->render('@admin/sms/queue/details.html.twig', [
            'queue' => $queue
        ]);
    }

}