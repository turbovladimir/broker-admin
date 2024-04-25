<?php

namespace App\Service\Checker;

use App\Entity\OfferCheckerRelation;
use App\Repository\OfferCheckerRelationRepository;
use App\Service\Checker\DTO\CheckerResult;

class Service
{
    public function __construct(private Adapter $adapter, private OfferCheckerRelationRepository $repository)
    {
    }

    public function checkPhone(string $phone) : CheckerResult
    {
        $report = $this->adapter->getReport($phone);

        $relations = $this->repository->findBy(['checker' => OfferCheckerRelation::CHECKER_LEAD_GID]);
        $externalIdsShouldExclude = [];

        foreach ($report as $item) {
            if ($item['NotExists'] === 'false') {
                $externalIdsShouldExclude = array_merge($externalIdsShouldExclude, $item['Offers']);
            }
        }

        $relations = array_filter($relations, fn(OfferCheckerRelation $relation) => in_array($relation->getExternalOfferId(), $externalIdsShouldExclude));

        return new CheckerResult($relations);
    }
}