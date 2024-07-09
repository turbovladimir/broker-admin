<?php

namespace App\EventSubscriber;

use App\Entity\Contact;
use App\Enums\ContactSource;
use App\Event\AdminCreateDistributionEvent;
use App\Service\Integration\Client;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\RequestOptions;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class DistributionEventsSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private Filesystem $filesystem,
        private ContainerBagInterface $containerBag,
        private LoggerInterface $logger
    )
    {}

    public function onAdminCreateDistributionEvent(AdminCreateDistributionEvent $event) : void
    {
        /** @var UploadedFile $file */
        $file = current($event->request->files->get('sms_queue'));
        $dir = $this->containerBag->get('kernel.project_dir') . '/var/tmp/contacts/new';

        if (!$this->filesystem->exists($dir)) {
            $this->filesystem->mkdir($dir);
        }

        $file->move($dir, $file->getClientOriginalName());
        $queue = $event->queue;
        $this->entityManager->persist($queue->setFilePath($dir . '/' . $file->getClientOriginalName()));
        $filePath = $queue->getFilePath();
        $handle = fopen($filePath, "r");

        if (!$handle) {
            throw new \RuntimeException(sprintf('Не удается получить доступ к файлу `%s`', $filePath));
        }

        while ($row = fgetcsv($handle)) {
            if (!isset($isFirstRow)) {
                $isFirstRow = true;

                continue;
            }

            preg_match('#\d{10}$#', $row[1], $matches);

            if (empty($matches)) {
                $this->logger->error('Unexpected phone number in file', ['row' => $row]);

                continue;
            }

            $phone = '+7' . $matches[0];


            $this->entityManager->persist(
                (new Contact($phone))
                ->setName($row[0])
                ->setQueue($queue)
                ->setSource(ContactSource::Distribution));
        }

        fclose($handle);
        $this->entityManager->flush();
    }

    public static function getSubscribedEvents()
    {
        return [
            AdminCreateDistributionEvent::class => 'onAdminCreateDistributionEvent'
        ];
    }
}