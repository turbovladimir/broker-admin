<?php

namespace App\Controller\Push;

use App\Entity\Push;
use App\Repository\PushRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/push', name: 'push_')]
class PushController extends AbstractController
{
    #[Route('/get', name: 'get', methods: 'POST')]
    public function get(Request $request, PushRepository $repository) : JsonResponse
    {
        $path = $request->request->get('path');
        $push = $repository->findOneBy(['uri' => $path, 'isActive' => true]);

        return new JsonResponse([
            'path' => $path,
            'push' => $push ? [
                'show_delay' => $push->getShowDelaySecs(),
                'text' => $push->getText(),
                'target' => $push->getTarget(),
            ] : []
        ]);
    }
}