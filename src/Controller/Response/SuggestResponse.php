<?php

namespace App\Controller\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Response for autocompliting inputs lib usage
 */
class SuggestResponse
{
    public static function create(string $query, array $suggestions) : JsonResponse
    {
        return new JsonResponse(
            [
                'query' => $query,
                'suggestions' => $suggestions
            ]
        );
    }
}