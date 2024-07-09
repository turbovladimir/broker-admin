<?php

namespace App\Service\Checker\LeadSu;

use App\Entity\OfferCheckerRelation;
use App\Repository\OfferCheckerRelationRepository;
use App\Service\BaseChecker;
use App\Service\Checker\CheckerInterface;
use App\Service\Checker\DTO\CheckerResult;
use App\Service\Integration\Client;
use GuzzleHttp\RequestOptions;
use Psr\Log\LoggerInterface;

class Checker extends BaseChecker implements CheckerInterface
{
    const TOKEN = '2bc7ec73206ee767b15bd69230c29b5c';

    public function __construct(
        private Client $client,
        private  OfferCheckerRelationRepository $checkerRelationRepository,
        private LoggerInterface $checkerLogger
    )
    {
        parent::__construct($this->checkerRelationRepository, $this->checkerLogger);
    }

    public function check(string $phone, CheckerResult $result) : void
    {
        $relations = $this->fetchCheckerRelation(OfferCheckerRelation::CHECKER_LEAD_SU);

        if (!$relations) {
            return;
        }

        $relationsWithExternalIdsKey = [];

        foreach ($relations as $relation) {
            $relationsWithExternalIdsKey[$relation->getExternalOfferId()] = $relation;
        }

        foreach ($this->getReport($phone, array_keys($relationsWithExternalIdsKey)) as $item) {
            if ($item['is_repeat'] === 1) {
                foreach ($item['offers'] as $externalOfferId) {
                    if (!empty($relationsWithExternalIdsKey[$externalOfferId])) {
                        $result->excludeOffer($relationsWithExternalIdsKey[$externalOfferId]->getOffer());
                    }
                }
            }
        }
    }

    private function getReport(string $phone, array $externalOfferIds) : array
    {
        $reportId = $this->createRequest($phone, $externalOfferIds);
        $url = sprintf('https://api.leads.su/webmaster/checker/getReport?id=%d&token=%s', $reportId, self::TOKEN);
        $res = $this->client->get($url);

        $report = $this->parseResponse($res);

        return $report['data'];
    }

    private function createRequest(string $phone, array $externalOfferIds) : int
    {
        $res = $this->client->post(sprintf('https://api.leads.su/webmaster/checker/checkPhones?token=%s', self::TOKEN), [
            RequestOptions::JSON => [
                'name' => 'string',
                'phones' => [$phone],
                'offers' => $externalOfferIds
            ]
        ]);


        return $this->parseResponse($res)['report_id'];
    }
}