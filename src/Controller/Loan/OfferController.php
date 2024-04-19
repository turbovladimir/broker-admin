<?php

namespace App\Controller\Loan;

use App\Entity\Offer;
use App\Form\OfferType;
use App\Repository\OfferRepository;
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
    #[Route('/show', name: 'show')]
    public function showList(OfferRepository $repository, Request $request): Response
    {
        $sId = $request->getSession()->getId();
        $offers = $repository->findBy(['isActive' => 1]);

        return new JsonResponse([
            'offer_list' => $this->renderView('@loan/offers/list.html.twig', ['offers' => $offers])
        ]);
    }
}
