<?php

namespace App\Controller\Loan;

use App\Service\Auth\DTO\SendCodeRequest;
use App\Service\Auth\DTO\VerifyCodeRequest;
use App\Service\Auth\Exception\PhoneVerify\ClientErrorAwareInterface;
use App\Service\Auth\PhoneVerifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/phone', name: 'phone_')]
class PhoneController extends AbstractController
{
    #[Route('/verify/request', name: 'request_verify')]
    public function requestVerify(Request $request, PhoneVerifier $phoneVerifier) : JsonResponse
    {
        $phoneVerifier->sendCode(SendCodeRequest::create($request));

        return new JsonResponse(['status' => 'ok']);
    }

    #[Route('/verify', name: 'code_verify', methods: 'POST')]
    public function verifyCode(Request $request, PhoneVerifier $phoneVerifier) : JsonResponse
    {
        try {
            $phoneVerifier->verify(VerifyCodeRequest::create($request));
            $request->getSession()->set(LoanController::FLAG_PHONE_VERIFIED, true);
        } catch (ClientErrorAwareInterface $exception) {
            //todo add logs for exceptions
            return new JsonResponse(['error' => $exception->getClientMessage()], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse(['status' => 'ok']);
    }
}