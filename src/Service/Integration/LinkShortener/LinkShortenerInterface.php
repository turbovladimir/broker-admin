<?php

namespace App\Service\Integration\LinkShortener;

interface LinkShortenerInterface
{
    public function shorting(string $url) : string;
}