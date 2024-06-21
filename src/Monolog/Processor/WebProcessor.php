<?php

namespace App\Monolog\Processor;

use Monolog\LogRecord;
use Monolog\Processor\ProcessorInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class WebProcessor implements ProcessorInterface
{
    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function __invoke(LogRecord $record)
    {
        $currentRequest = $this->requestStack->getCurrentRequest();

        if ($currentRequest) {
            $record['extra'] = [
                'request' => [
                    'ip' => $request->headers->get('x-forwarded-for') ?? $currentRequest->getClientIp(),
                    'uri' => $currentRequest->getUri(),
                    'method' => $currentRequest->getMethod(),
                    'query' => $currentRequest->query->all(),
                    'post_data' => $currentRequest->request->all()
                ]
            ];
        }

        return $record;
    }
}