<?php

namespace App\Controller\Api;

use App\Controller\Session;
use App\Repository\ContactRepository;
use App\Service\Checker\Service;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api', name: 'api_')]
class ApiController extends AbstractController
{

    #[Route('/user/reg', name: 'user_register')]
    public function registerUser(Request $request, Service $service, ContactRepository $repository) : JsonResponse
    {
        $s = $request->getSession();

        $contact = $repository->findOneBy(['contactId' => $s->get(Session::Contact->value)]);

        if (!$contact) {
            return new JsonResponse([
                'error' => 'Cannot find user'
            ], Response::HTTP_BAD_REQUEST);
        }

        $offerIds = $service->checkPhone($contact->getPhone())->getExcludeOfferIds();

        $s->set(Session::ExcludeOfferIds->value, $offerIds);

        return new JsonResponse(['data' => [
            'filter' => $offerIds,
            'redirect_url' => $this->generateUrl('user_offer_index')
        ]]);
    }
}