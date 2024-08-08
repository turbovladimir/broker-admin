<?php

namespace App\EventSubscriber;

use App\Entity\Contact;
use App\Enums\ContactSource;
use App\Event\AdminCreateDistributionEvent;
use Doctrine\ORM\EntityManagerInterface;
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

        $n = 1;
        $phones = [];

        while ($row = fgetcsv($handle, null , ';')) {
            if (!isset($isFirstRow)) {
                $isFirstRow = true;

                continue;
            }

            if (empty($row)) {
                throw new \RuntimeException(sprintf('Не удалось распарсить файл'));

            }

            preg_match('#\d{10}$#', $row[1], $matches);

            if (empty($matches)) {
                throw new \RuntimeException(sprintf('Не удалось распарсить телефон! Строка `%s`(%d)', implode(',', $row), $n));
            }

            $phone = '+7' . $matches[0];

            //skip dupes
            if (in_array($phone, $phones)) {
                continue;
            }

            $this->entityManager->persist(
                (new Contact($phone))
                ->setName('Вася Пупкин')
                ->setQueue($queue)
                ->setSource(ContactSource::Distribution));

            $phones[] = $phone;
            $n++;
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