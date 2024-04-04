<?php

namespace App\Controller\Loan;

use App\Entity\LoanRequest;
use App\Entity\Offer;
use App\Entity\Push;
use App\Form\LoanRequestType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/loan', name: 'loan_')]
class LoanController extends AbstractController
{
    const FLAG_PHONE_VERIFIED = 'verified';

    #[Route('/test', name: 'test')]
    public function test()
    {
        return $this->render('@loan/test.html.twig');
    }

    #[Route('/welcome', name: 'welcome')]
    public function welcome(Request $request) : Response
    {
        if ($request->getSession()->get(self::FLAG_PHONE_VERIFIED, false)) {
            return $this->redirectToRoute('loan_form');
        }

        return $this->render('@loan/welcome/index.html.twig');
    }

    #[Route('/offers', name: 'offers')]
    public function offers()
    {
        return $this->render('@loan/welcome/index.html.twig');
    }

    #[Route('/form', name: 'form')]
    public function form(Request $request, EntityManagerInterface $entityManager) : Response
    {
        $loanRequest = new LoanRequest();
        $loanRequest->setAddedAt(new \DateTime());
        $form = $this->createForm(LoanRequestType::class, $loanRequest);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->render('@loan/form/index.html.twig', [
                'form' => $form->createView(),
                'verified' => $request->getSession()->get(self::FLAG_PHONE_VERIFIED, false)
            ]);
        }

        $entityManager->persist($loanRequest);
        $entityManager->flush();

        // get offer filter by session cookie
        $offers = $entityManager->getRepository(Offer::class)->findAll();

        return new JsonResponse([
            'offer_list' => $this->renderView('@loan/offers/list.html.twig', ['offers' => $offers])
        ]);
    }
}