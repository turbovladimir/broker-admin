<?php

namespace App\Controller\Dadata;

use App\Controller\Response\SuggestResponse;
use App\Service\DaData\SuggesterAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/suggest', name: 'suggest_')]
class SuggestController extends AbstractController
{
    #[Route('/city', name: 'city')]
    public function suggestCity(Request $request, SuggesterAdapter $suggester): JsonResponse
    {
        $query = $request->query->get('query');

        return SuggestResponse::create($query, $suggester->suggestCity($query));
    }

    #[Route('/address', name: 'address')]
    public function suggestAddress(Request $request, SuggesterAdapter $suggester): JsonResponse
    {
        $query = $request->query->get('query');

        return SuggestResponse::create($query, $suggester->suggestAddress($query));
    }

    #[Route('/department_code', name: 'department_code')]
    public function suggestDepartmentCode(Request $request, SuggesterAdapter $suggester): JsonResponse
    {
        $query = $request->query->get('query');

        return SuggestResponse::create($query, $suggester->suggestDepartmentCode($query));
    }
}