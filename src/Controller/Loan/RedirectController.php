<?php

namespace App\Controller\Loan;

use App\Controller\Session;
use App\Entity\Contact;
use App\Enums\Macros;
use App\Enums\RegistrationType;
use App\Repository\ContactRepository;
use App\Repository\OfferRepository;
use App\Service\Checker\Service;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RedirectController extends AbstractController
{
    #[Route('r', name: 'redirect_view', methods: ['GET'])]
    public function redirectView() : Response
    {
        return $this->render('@loan/redirect.html.twig');
    }

    #[Route('r', name: 'redirect_action', methods: ['POST'])]
    public function redirectAction(
        Request $request,
        Service $service,
        ContactRepository $repository,
        OfferRepository $offerRepository,
    ) : JsonResponse
    {
        $s = $request->getSession();

        if ($s->has(Session::ContactHash->value)) {
            $contactIdHashed = $s->get(Session::ContactHash->value);
        } else {
            $contactIdHashed = $request->query->get('c');
            $s->set(Session::ContactHash->value, $contactIdHashed);
        }

        $contact = $repository->findOneBy(['contactId' => $contactIdHashed]);
        $redirectUrl = $this->generateUrl('loan_welcome');

        if ($contact) {
            $s->set(Session::RegistrationType->value, RegistrationType::Short->value);
            $offer = $offerRepository->find($request->query->get('o'));
            $excludeOfferIds = $this->fetchExcludedOffers($request, $service, $contact);
            $redirectUrl = $this->generateUrl('user_offer_index');

            if ($offer && $offer->isIsActive() && !in_array($offer->getId(), $excludeOfferIds)) {
                $redirectUrl = str_replace(Macros::ContactId->value, $contactIdHashed, $offer->getUrl());
            }

            $s->set(Session::ExcludeOfferIds->value, $excludeOfferIds);
        }

        return new JsonResponse(['data' => [
            'redirect_url' => $redirectUrl
        ]]);
    }

    private function fetchExcludedOffers(
        Request $request,
        Service $service,
        Contact $contact
    ) : array
    {
        $s = $request->getSession();

        if ($s->has(Session::ExcludeOfferIds->value)) {
            return $s->get(Session::ExcludeOfferIds->value);
        }

        return $service->checkPhone($contact->getPhone(), true)->getExcludeOfferIds();
    }
}