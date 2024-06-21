<?php

namespace App\Controller\Admin;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/m', name: 'admin_monitoring_')]
class MonitoringController extends AbstractController
{
    #[Route('/sms/status', name: '_sms_status')]
    public function smsStatus(Request $request, LoggerInterface $smsLogger) : JsonResponse
    {
        $smsLogger->info('Get sms status from vendor...', [
            'request' => [
                'method' => $request->getMethod(),
                'query' => $request->query->all(),
                'request_parsed_params' => $request->request->all()
            ]
        ]);
        return new JsonResponse();
    }

    #[Route('/healthcheck', name: '_healthcheck')]
    public function healthCheck() : JsonResponse
    {
        return new JsonResponse();
    }
}