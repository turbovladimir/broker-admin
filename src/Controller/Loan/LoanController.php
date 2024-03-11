<?php

namespace App\Controller\Loan;

use App\Entity\LoanRequest;
use App\Form\LoanRequestType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/loan', name: 'loan_')]
class LoanController extends AbstractController
{
    #[Route('/form', name: 'form')]
    public function form(Request $request, EntityManagerInterface $entityManager)
    {
        $loanRequest = new LoanRequest();
        $loanRequest->setAddedAt(new \DateTime());
        $form = $this->createForm(LoanRequestType::class, $loanRequest);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->render('@loan/form/index.html.twig', ['form' => $form->createView()]);
        }

        $entityManager->persist($loanRequest);
        $entityManager->flush();

        return $this->redirectToRoute('loan_thanks');
    }
}