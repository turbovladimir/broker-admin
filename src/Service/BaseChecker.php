<?php

namespace App\Service;

use App\Entity\OfferCheckerRelation;
use App\Repository\OfferCheckerRelationRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

abstract class BaseChecker
{
    public function __construct(private OfferCheckerRelationRepository $checkerRelationRepository, protected LoggerInterface $logger)
    {
    }

    protected function fetchCheckerRelation(string $checkerName) : ?array
    {
        $relations = $this->checkerRelationRepository->findBy(['checker' => $checkerName]);

        if (empty($relations)) {
            $this->logger->warning('Checker has not configured yet.', ['checker' => $this::class]);

            return null;
        }

        return $relations;
    }

    protected function parseResponse(ResponseInterface $response) : array
    {
        $report = json_decode((string)$response->getBody(), true);

        return $report;
    }
}