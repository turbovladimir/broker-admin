<?php

namespace App\Controller\Loan;

use App\Entity\Offer;
use App\Form\OfferType;
use App\Repository\OfferRepository;
use App\Service\Auth\Access\SessionFlags;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/loan/offer', name: 'user_offer_')]
class OfferController extends AbstractController
{
    const OFFER_LIMIT_ON_PAGE = 12;
    const SESSION_EXCLUDE_OFFER_IDS = 'exclude_offer_ids';

    #[Route('/index', name: 'index')]
    public function index(OfferRepository $repository, Request $request): Response
    {
        if (!$request->getSession()->get(SessionFlags::USER_REGISTERED, false)) {
            return $this->redirectToRoute('loan_welcome');
        }

        $excludeOfferIds = $request->getSession()->get(self::SESSION_EXCLUDE_OFFER_IDS);
        $offers = $repository->findOffersForUser($excludeOfferIds ?? [], self::OFFER_LIMIT_ON_PAGE);

        return $this->render('@loan/offers/index.html.twig', [
            'offers' => $offers
        ]);
    }

    #[Route('/show', name: 'show')]
    public function showList(OfferRepository $repository, Request $request): Response
    {
        $excludeOfferIds = $request->getSession()->get(self::SESSION_EXCLUDE_OFFER_IDS);
        $offers = $repository->findOffersForUser($excludeOfferIds ?? [], self::OFFER_LIMIT_ON_PAGE);

        return new JsonResponse([
            'offer_list' => $this->renderView('@loan/offers/list.html.twig', [
                'offers' => $offers
            ])
        ]);
    }
}
