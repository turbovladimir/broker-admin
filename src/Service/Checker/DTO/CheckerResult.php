<?php

namespace App\Service\Checker\DTO;

use App\Entity\OfferCheckerRelation;

class CheckerResult
{
    private array $excludedOfferIds = [];
    /**
     * @param []OfferCheckerRelation $relations
     */
    public function __construct(array $relations)
    {
        /** @var OfferCheckerRelation $relation */
        foreach ($relations as $relation) {
            $this->excludedOfferIds[] = $relation->getOffer()->getId();
        }
    }

    public function getExcludeOfferIds() : array
    {
        return $this->excludedOfferIds;
    }
}