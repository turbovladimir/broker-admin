<?php

namespace App\Controller\Loan;

use App\Entity\LoanRequest;
use App\Entity\Offer;
use App\Entity\PhoneVerifyJob;
use App\Entity\Push;
use App\Form\LoanRequestType;
use App\Service\Auth\Access\SessionFlags;
use App\Service\Checker\Service;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/loan', name: 'loan_')]
class LoanController extends AbstractController
{

    #[Route('/welcome', name: 'welcome')]
    public function welcome(Request $request) : Response
    {
        if ($request->getSession()->get(SessionFlags::PHONE_VERIFIED, false)) {
            return $this->redirectToRoute('loan_form_show');
        }

        if ($request->getSession()->get(SessionFlags::USER_REGISTERED, false)) {
            return $this->redirectToRoute('user_offer_index');
        }

        $request->getSession()->set(SessionFlags::USER_REGISTERED, false);

        return $this->render('@loan/welcome/index.html.twig');
    }

    #[Route('/form', name: 'form_show')]
    public function formShow(Request $request) : Response
    {
        if (!$request->getSession()->get(SessionFlags::PHONE_VERIFIED, false)) {
            return $this->redirectToRoute('loan_welcome');
        }

        if ($request->getSession()->get(SessionFlags::USER_REGISTERED, false)) {
            return $this->redirectToRoute('user_offer_index');
        }

        $loanRequest = new LoanRequest();
        $loanRequest->setAddedAt(new \DateTime());

        return $this->render('@loan/form/index.html.twig', [
            'form' => $this->createForm(LoanRequestType::class, $loanRequest)->createView(),
            'verified' => $request->getSession()->get(SessionFlags::PHONE_VERIFIED, false)
        ]);
    }

    #[Route('/form/submit', name: 'form_submit', methods: ['POST'])]
    public function formSubmit(Request $request, EntityManagerInterface $entityManager, LoggerInterface $telegramLogger) : Response
    {
        if (!$request->getSession()->get(SessionFlags::PHONE_VERIFIED, false)) {
            return $this->redirectToRoute('loan_welcome');
        }

        $loanRequest = new LoanRequest();
        $loanRequest->setAddedAt(new \DateTime());
        $form = $this->createForm(LoanRequestType::class, $loanRequest);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return new JsonResponse([
                'error' => $form->getErrors()
            ], 400);
        }

        try {
            $job = $entityManager->getRepository(PhoneVerifyJob::class)->findOneBy([
                'isActive' => 1,
                'isVerified' => 1,
                'sessionId' => $request->getSession()->getId()
            ]);

            if (!$job) {
                throw new \LogicException('Verified phone not found, but form try submit');
            }

            $loanRequest->setPhone($job->getPhone());
            $entityManager->persist($job->setIsActive(false));
            $entityManager->persist($loanRequest);
            $entityManager->flush();
            $request->getSession()->set(SessionFlags::USER_REGISTERED, true);
        } catch (\Throwable $exception) {
            $telegramLogger->notice('Error during save loan entity in db', [
                'error' => $exception->getMessage(),
                'trace' => $exception->getTrace()
            ]);

            return new JsonResponse([
                'error' => 'Что то пошло не так'
            ], 500);
        }

        return new JsonResponse();
    }
}