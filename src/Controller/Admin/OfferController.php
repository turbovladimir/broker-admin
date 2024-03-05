<?php

namespace App\Controller\Admin;

use App\Entity\Offer;
use App\Form\OfferType;
use App\Repository\OfferRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/offer', name: 'offer_')]
class OfferController extends AbstractController
{
    #[Route('/list', name: 'list')]
    public function list(OfferRepository $repository, LoggerInterface $telegramLogger): Response
    {
        $offers = $repository->findAll();

        return $this->render('admin/offer/list.html.twig', ['offers' => $offers]);
    }

    #[Route('/edit/{offer}', name: 'edit')]
    public function edit(Offer $offer, Request $request, EntityManagerInterface $entityManager, FileUploader $uploader)
    {
        $form = $this->createForm(OfferType::class, $offer);
        $form->handleRequest($request);

        if ($this->upsertOffer($offer, $form, $entityManager, $uploader)) {
            return $this->redirectToRoute('offer_list');
        }

        return $this->render('admin/offer/edit.html.twig', [
            'form' => $form->createView(),
            'offer' => $offer
        ]);
    }

    #[Route('/new', name: 'new')]
    public function new(Request $request, EntityManagerInterface $entityManager, FileUploader $uploader): Response
    {
        $offer = new Offer();
        $form = $this->createForm(OfferType::class, $offer);
        $form->handleRequest($request);

        if ($this->upsertOffer($offer, $form, $entityManager, $uploader)) {
            return $this->redirectToRoute('offer_list');
        }

        return $this->render('admin/offer/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function upsertOffer(Offer $offer, FormInterface $form, EntityManagerInterface $entityManager, FileUploader $uploader) : bool
    {
        if (!$form->isSubmitted() || !$form->isValid()) {
            return false;
        }

        $uploadFile = $form->get('logotype')->getData();

        if ($uploadFile) {
            $fileName = $uploader->upload($uploadFile, $this->getParameter('dir_logo'));
            $offer->setLogo($fileName);
        }

        $entityManager->persist($offer);
        $entityManager->flush();


        return true;
    }
}
