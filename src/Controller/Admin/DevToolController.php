<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/dev', name: 'admin_dev_')]

class DevToolController extends AbstractController
{
    #[Route('/session/reset', name: 'session_reset')]
    public function resetSession(Request $request): Response
    {
        if (strtolower($this->getParameter('kernel.environment')) === 'dev') {
            $request->getSession()->clear();
        }

        return $this->redirectToRoute('redirect_main');
    }
}