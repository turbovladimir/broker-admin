<?php

namespace App\Service\Checker\DTO;

class CheckerResult
{
    private array $excludedOfferIds = [];

    /**
     * @param []OfferCheckerRelation $relations
     */
    public function add(array $excludeIds)
    {
        foreach ($excludeIds as $excludeId) {
            if (!in_array($excludeId, $this->excludedOfferIds)) {
                $this->excludedOfferIds[] = $excludeId;
            }
        }
    }

    public function getExcludeOfferIds() : array
    {
        return $this->excludedOfferIds;
    }
}