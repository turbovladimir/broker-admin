<?php

namespace App\Service\Integration\LinkShortener;

use App\Service\Integration\Client;
use GuzzleHttp\RequestOptions;

class Adapter implements LinkShortenerInterface
{
    public function __construct(
        private Client $client
    ){}

    public function shorting(string $url): string
    {
        $endpoint = 'https://clck.ru/--';
        $resp = $this->client->request('GET', $endpoint, [
           RequestOptions::QUERY => [
               'url' => $url
           ]
        ]);

        return (string)$resp->getBody();
    }
}