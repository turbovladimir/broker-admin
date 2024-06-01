<?php

namespace App\Service\Checker\DTO;

use App\Entity\Offer;

class CheckerResult
{
    private array $excludedOffers = [];

    public function excludeOffer(Offer $offer)
    {
        if (!isset($this->excludedOffers[$offer->getId()])) {
            $this->excludedOffers[] = $offer;
        }
    }

    public function getExcludeOfferIds() : array
    {
        return array_map(fn(Offer $offer) => $offer->getId(), $this->excludedOffers);
    }
}