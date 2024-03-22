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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/loan/offer', name: 'user_offer_')]
class OfferController extends AbstractController
{
    #[Route('/list', name: 'list')]
    public function list(OfferRepository $repository): Response
    {
        $offers = $repository->findBy(['isActive' => 1]);

        return $this->render('@loan/offers/index.html.twig', ['offers' => $offers]);
    }
}
