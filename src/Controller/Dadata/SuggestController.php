<?php

namespace App\Controller\Dadata;

use App\Service\DaData\AddressSuggester;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/suggest', name: 'suggest_')]
class SuggestController extends AbstractController
{
    #[Route('/city', name: 'city')]
    public function suggestCity(Request $request, AddressSuggester $suggester): Response
    {
        $query = $request->query->get('query');

        return SuggestResponse::create($query, $suggester->suggestCity($query));
    }

    #[Route('/address', name: 'address')]
    public function suggestAddress(Request $request, AddressSuggester $suggester): Response
    {
        $query = $request->query->get('query');

        return SuggestResponse::create($query, $suggester->suggestAddress($query));
    }

    #[Route('/department_code', name: 'department_code')]
    public function suggestDepartmentCode(Request $request, AddressSuggester $suggester): Response
    {
        $query = $request->query->get('query');

        return SuggestResponse::create($query, $suggester->suggestDepartmentCode($query));
    }
}