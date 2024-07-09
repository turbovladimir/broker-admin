<?php

namespace App\Service\Checker\LeadGid;

use App\Entity\OfferCheckerRelation;
use App\Repository\OfferCheckerRelationRepository;
use App\Service\BaseChecker;
use App\Service\Checker\CheckerInterface;
use App\Service\Checker\DTO\CheckerResult;
use App\Service\Integration\Client;
use App\Service\Integration\Exception\InvalidResponseBodyException;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

class Checker extends BaseChecker implements CheckerInterface
{
    const TOKEN = 'e515e56f49e43c0ad99ce5d4290084b1';

    public function __construct(
        private Client $client,
        private  OfferCheckerRelationRepository $checkerRelationRepository,
        private LoggerInterface $checkerLogger
    )
    {
        parent::__construct($this->checkerRelationRepository, $this->checkerLogger);
    }

    public function check(string $phone, CheckerResult $result): void
    {
        $relations = $this->fetchCheckerRelation(OfferCheckerRelation::CHECKER_LEAD_GID);

        if (!$relations) {
            return;
        }

        $relations = array_combine(
            array_map(fn(OfferCheckerRelation $relation) => $relation->getExternalOfferId(), $relations),
            $relations
        );
        $report = $this->getReport($phone);

        foreach ($report as $item) {
            $externalOfferId = $item['Offers'][0];

            if (!$item['NotExists'] && key_exists($externalOfferId, $relations)) {
                /** @var OfferCheckerRelation $relation */
                $relation = $relations[$externalOfferId];
                $result->excludeOffer($relation->getOffer());
            }
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

        return $this->parseResponse($res);
    }

    private function validateResponseBody(array $bodyData) : void
    {
        if (empty($bodyData) || $bodyData['success'] !== true) {
            throw new InvalidResponseBodyException(json_encode($bodyData));
        }
    }

    protected function parseResponse(ResponseInterface $response): array
    {
        $bodyData = parent::parseResponse($response);
        $this->validateResponseBody($bodyData);

        return  $bodyData['data'];
    }
}