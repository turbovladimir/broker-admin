<?php

namespace App\Controller\Loan;

use App\Controller\Session;
use App\Entity\Contact;
use App\Enums\Macros;
use App\Enums\RegistrationType;
use App\Repository\ContactRepository;
use App\Repository\OfferRepository;
use App\Service\Checker\Service;
use Psr\Log\LoggerInterface;
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
        LoggerInterface $smsLogger
    ) : JsonResponse
    {
        $s = $request->getSession();
        $smsLogger->info('begin redirect process...');

        if ($s->has(Session::ContactHash->value)) {
            $smsLogger->info('get contact id from session');
            $contactIdHashed = $s->get(Session::ContactHash->value);
        } else {
            $contactIdHashed = $request->query->get('c');
            $s->set(Session::ContactHash->value, $contactIdHashed);
            $smsLogger->info('get contact id from request and store in session', ['contact_hash' => $contactIdHashed]);
        }

        $contact = $repository->findOneBy(['contactId' => $contactIdHashed]);
        $redirectUrl = $this->generateUrl('loan_welcome');

        if ($contact) {
            $smsLogger->info('Contact exist. Register...', ['contact_id' => $contact->getId()]);

            $s->set(Session::RegistrationType->value, RegistrationType::Short->value);
            $offer = $offerRepository->find($request->query->get('o'));
            $smsLogger->info('Fetch offer', ['offer_id' => $offer?->getId()]);

            $excludeOfferIds = $service
                ->checkPhone($contact->getPhone(), true)->getExcludeOfferIds();
            $redirectUrl = $this->generateUrl('user_offer_index');

            if ($offer && $offer->isIsActive() && !in_array($offer->getId(), $excludeOfferIds)) {
                $redirectUrl = str_replace(Macros::ContactId->value, $contactIdHashed, $offer->getUrl());
                $smsLogger->info('Use offer for redirection');
            }

            $s->set(Session::ExcludeOfferIds->value, $excludeOfferIds);
        }

        $smsLogger->info('Redirect on', ['redirect_url' => $redirectUrl]);


        return new JsonResponse(['data' => [
            'redirect_url' => $redirectUrl
        ]]);
    }
}