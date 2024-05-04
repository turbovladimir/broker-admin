<?php

namespace App\Controller\File;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/file', name: 'file_')]
class Controller extends AbstractController
{
    #[Route('/{fileName}/download', name: 'download')]
    public function download(string $fileName) : Response
    {
        $filePath = $this->getParameter('kernel.project_dir') . '/doc/loan/'. $fileName;

        if (!is_file($filePath)) {
            return new Response('File not found', 400);
        }

        $response = new BinaryFileResponse($filePath);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT);

        return $response;
    }
}