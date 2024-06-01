<?php

namespace App\Service\Checker\LeadCraft;

use App\Entity\OfferCheckerRelation;
use App\Repository\OfferCheckerRelationRepository;
use App\Service\BaseChecker;
use App\Service\Checker\CheckerInterface;
use App\Service\Checker\DTO\CheckerResult;
use App\Service\Rest\Client;
use App\Service\Rest\Exception\InvalidResponseBodyException;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

class Checker extends BaseChecker implements CheckerInterface
{
    const TOKEN = '1716559943.36722sRMDAeZ7tM4K91q';

    public function __construct(
        private Client $client,
        private OfferCheckerRelationRepository $checkerRelationRepository,
        private LoggerInterface $checkerLogger
    )
    {
        parent::__construct($this->checkerRelationRepository, $this->checkerLogger);
    }

    public function check(string $phone, CheckerResult $result): void
    {
        $relations = $this->fetchCheckerRelation(OfferCheckerRelation::CHECKER_LEAD_CRAFT);

        if (!$relations) {
            return;
        }

        $relations = array_combine(
            array_map(fn(OfferCheckerRelation $relation) => $relation->getExternalOfferId(), $relations),
            $relations
        );
        $report = $this->getReport($phone);

        foreach ($report as $item) {
            if ($item['isDuplicate'] && key_exists($item['id'], $relations)) {

                /** @var OfferCheckerRelation $relation */
                $relation = $relations[$item['id']];
                $result->excludeOffer($relation->getOffer());
            }
        }
    }

    private function getReport(string $phone) : array
    {
        $res = $this->client->get('https://api.leadcraft.ru/v1/services/checked-feed', [
            RequestOptions::HEADERS => [
                'Authorization' => 'Bearer ' . self::TOKEN
            ],
            RequestOptions::QUERY => [
                'phone' => ltrim($phone, '+')
            ]
        ]);


        $bodyData = $this->parseResponse($res);

        return $bodyData['data'];
    }
}