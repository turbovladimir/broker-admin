<?php

namespace App\Service\Checker\LeadGid;

use App\Entity\OfferCheckerRelation;
use App\Repository\OfferCheckerRelationRepository;
use App\Service\Checker\CheckerInterface;
use App\Service\Checker\DTO\CheckerResult;
use App\Service\Rest\Client;
use App\Service\Rest\Exception\InvalidResponseBodyException;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

class Checker implements CheckerInterface
{
    const TOKEN = 'e515e56f49e43c0ad99ce5d4290084b1';

    public function __construct(
        private Client $client,
        private  OfferCheckerRelationRepository $checkerRelationRepository,
        private LoggerInterface $checkerLogger
    ){}

    public function check(string $phone, CheckerResult $result): void
    {
        $relations = $this->checkerRelationRepository->findBy(['checker' => OfferCheckerRelation::CHECKER_LEAD_GID]);

        if (empty($relations)) {
            $this->checkerLogger->warning('Checker has not configured yet.');

            return;
        }

        $report = $this->getReport($phone);
        $externalIdsShouldExclude = [];

        foreach ($report as $item) {
            if ($item['NotExists'] === false) {
                $externalIdsShouldExclude = array_merge($externalIdsShouldExclude, $item['Offers']);
            }
        }

        $relations = array_filter($relations, fn(OfferCheckerRelation $relation) => in_array($relation->getExternalOfferId(), $externalIdsShouldExclude));

        if(!empty($relations)) {
            $result->add(array_map(fn(OfferCheckerRelation $relation) => $relation->getOffer()->getId(), $relations));
        }
    }

    private function getReport(string $phone) : array
    {
        $res = $this->client->post('https://phone-checker.lead-core.ru/check', [
            RequestOptions::HEADERS => [
                'Authorization' => self::TOKEN
            ],
            RequestOptions::JSON => [
                'async' => false,
                'phone' => $phone
            ]
        ]);


        $bodyData = $this->parseResponse($res);

        return $bodyData['data'];
    }

    private function validateResponseBody(array $bodyData) : void
    {
        if (empty($bodyData) || $bodyData['success'] !== true) {
            throw new InvalidResponseBodyException(json_encode($bodyData));
        }
    }

    private function parseResponse(ResponseInterface $response) : array
    {
        $data = json_decode((string)$response->getBody(), true);
        $this->validateResponseBody($data);

        return $data;
    }
}