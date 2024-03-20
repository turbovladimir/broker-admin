<?php

namespace App\Controller\Dadata;

use Dadata\DadataClient;
use Dadata\Settings;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/suggest', name: 'suggest_')]
class SuggestController extends AbstractController
{
    #[Route('/city', name: 'city')]
    public function suggestCity(Request $request): Response
    {
        $query = $request->query->get('query');
        $token = "8a5f08f3190d2d5ed7f8fb3677f74d1d05c7a0bf";
        $dadata = new DadataClient($token, null);
        $options = ['locations' => ['country_iso_code' => 'RU'],'from_bound' => ['value' => 'city'],'to_bound' => ['value' => 'city']];
        $response = $dadata->suggest("address", "моск", Settings::SUGGESTION_COUNT, $options);

        return new JsonResponse(
            [
                'query' => $query,
                'suggestions' => [
                    'city' => array_map(fn($addressData) => $addressData['data']['city'], $response),
                    'region' => array_map(fn($addressData) => $addressData['data']['region'], $response),
                ],
            ]
        );
    }
}