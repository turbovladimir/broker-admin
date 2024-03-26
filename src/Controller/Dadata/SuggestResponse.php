<?php

namespace App\Controller\Dadata;

use Symfony\Component\HttpFoundation\JsonResponse;

class SuggestResponse
{
    public static function create(string $query, array $suggestions)
    {
        return new JsonResponse(
            [
                'query' => $query,
                'suggestions' => $suggestions
            ]
        );
    }
}