<?php

namespace App\Controller\Loan;

use App\Entity\LoanRequest;
use App\Entity\Offer;
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
    #[Route('/test', name: 'test')]
    public function test()
    {
        return $this->render('@loan/test.html.twig');
    }

    #[Route('/welcome', name: 'welcome')]
    public function welcome()
    {
        return $this->render('@loan/welcome/index.html.twig');
    }

    #[Route('/offers', name: 'offers')]
    public function offers()
    {
        return $this->render('@loan/welcome/index.html.twig');
    }

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

        // get offer filter by session cookie
        $offers = $entityManager->getRepository(Offer::class)->findAll();

        return new JsonResponse([
            'offer_list' => $this->renderView('@loan/offers/list.html.twig', ['offers' => $offers])
        ]);
    }

    #[Route('/verify/request_code', name: 'verify_request_code')]
    public function verifyRequestCode(Request $request, EntityManagerInterface $entityManager)
    {
        return new JsonResponse(['status' => 'ok']);
    }

    #[Route('/verify/{code}', name: 'code_verify')]
    public function codeVerify(int $code)
    {
        $length = ceil(log10(abs($code) + 1));

        if ($length != 4) {
            return new JsonResponse(['error' => 'Invalid code'], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse(['status' => 'ok']);
    }
}