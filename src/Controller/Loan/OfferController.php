<?php

namespace App\Controller\Loan;

use App\Controller\Session;
use App\Entity\Offer;
use App\Enums\Macros;
use App\Repository\OfferRepository;
use App\Service\Auth\Access\UserActionsChecker;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/loan/offer', name: 'user_offer_')]
class OfferController extends AbstractController
{
    const OFFER_LIMIT_ON_PAGE = 12;

    #[Route('/index', name: 'index')]
    public function index(OfferRepository $repository, Request $request, UserActionsChecker $actionChecker, LoggerInterface $logger): Response
    {
        if (!$actionChecker->isUserRegistered($request)) {
            return $this->redirectToRoute('loan_welcome');
        }

        return $this->render('@loan/offers/index.html.twig', [
            'offers' => $this->fetchOffers($request, $logger, $repository)
        ]);
    }

    #[Route('/show', name: 'show')]
    public function showList(OfferRepository $repository, Request $request, LoggerInterface $logger): Response
    {
        return new JsonResponse([
            'offer_list' => $this->renderView('@loan/offers/list.html.twig', [
                'offers' => $this->fetchOffers($request, $logger, $repository)
            ])
        ]);
    }

    private function fetchOffers(Request $request, LoggerInterface $logger, OfferRepository $offerRepository) : array
    {
        $s = $request->getSession();

        $excludeOfferIds = $s->get(Session::ExcludeOfferIds->value) ??
            $request->query->get('f') ??
            []
        ;

        $offers = $offerRepository->findOffers($excludeOfferIds, self::OFFER_LIMIT_ON_PAGE);

        if (empty($offers)) {
            $logger->warning('Офферы не найдены.');

            return [];
        }

        $contactId = $s->get(Session::ContactHash->value);

        if (!$contactId) {
            $logger->warning('Показ офферов без трекинга');
            $contactId = '';
        }

        /** @var Offer $offer */
        foreach ($offers as $offer) {
            $offer->setUrl(str_replace(Macros::ContactId->value, $contactId, $offer->getUrl()));
        }

        return $offers;
    }
}
