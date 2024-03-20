<?php

namespace App\Service;

use App\Event\UploadFileFinishEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    public function __construct(
        private SluggerInterface $slugger,
        private EventDispatcherInterface $eventDispatcher
    ) {
    }

    public function upload(UploadedFile $file, string $targetDir): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = substr($this->slugger->slug($originalFilename), 0 , 30);
        $fileName = $safeFilename.'.'.$file->guessExtension();

        try {
            $file->move($targetDir, $fileName);
            $this->eventDispatcher->dispatch(new UploadFileFinishEvent());
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        return $fileName;
    }
}