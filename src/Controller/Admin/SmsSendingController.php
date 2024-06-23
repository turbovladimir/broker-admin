<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
use App\Entity\SendingSmsJob;
use App\Entity\Sms;
use App\Entity\SmsQueue;
use App\Form\DTO\StartSendingRequest;
use App\Form\SmsQueueType;
use App\Form\StartSendingType;
use App\Repository\SmsQueueRepository;
use App\Service\Rest\Client;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Http\Client\ClientInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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
    public function queueCreate(Request $request, EntityManagerInterface $entityManager, Filesystem $filesystem) : Response
    {
        $queue = new SmsQueue();
        $form = $this->createForm(SmsQueueType::class, $queue);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = current($request->files->get('sms_queue'));
            $dir = $this->getParameter('kernel.project_dir') . '/var/tmp/contacts/new';

            if (!$filesystem->exists($dir)) {
                $filesystem->mkdir($dir);
            }

            $file->move($dir, $file->getClientOriginalName());
            $entityManager->persist($queue->setFilePath($dir . '/' . $file->getClientOriginalName()));
            $entityManager->flush();

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
            $jobs = [];

            foreach ($settings as $setting) {
                //todo add redirect type
                $jobs[] = (new SendingSmsJob($setting['sending_time']))->setSms(new Sms($setting['message']));
            }

            $queue->getContacts()->map(function (Contact $contact) use ($jobs) {
                foreach ($jobs as $job) {
                    $contact->addSendingSmsJob($job);
                }
            });

            $entityManager->persist($queue);
            $entityManager->flush();

            return $this->render('@admin/sms/queue/list.html.twig');
        }

        return $this->render('@admin/sms/queue/start.html.twig', [
            'queue' => $queue,
            'form' => $form->createView()
        ]);
    }

}