<?php

namespace App\Controller\Loan;

use App\Event\UserRequestVerifyPhoneEvent;
use App\Form\Exception\ClientErrorAwareInterface;
use App\Service\Auth\Access\SessionFlags;
use App\Service\Auth\DTO\VerifyCodeRequest;
use App\Service\Auth\PhoneVerifier;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/phone', name: 'phone_')]
class PhoneController extends AbstractController
{
    #[Route('/verify/request', name: 'request_verify')]
    public function requestVerify(Request $request, EventDispatcherInterface $dispatcher) : JsonResponse
    {
        try {
            $dispatcher->dispatch(new UserRequestVerifyPhoneEvent($request));
        } catch (ClientErrorAwareInterface $exception) {
            return new JsonResponse(['error' => $exception->getClientMessage()], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse(['status' => 'ok']);
    }

    #[Route('/verify', name: 'code_verify', methods: 'POST')]
    public function verifyCode(Request $request, PhoneVerifier $phoneVerifier) : JsonResponse
    {
        try {
            $phoneVerifier->verify(VerifyCodeRequest::create($request));
            $request->getSession()->set(SessionFlags::PHONE_VERIFIED, true);
        } catch (ClientErrorAwareInterface $exception) {
            //todo add logs for exceptions
            return new JsonResponse(['error' => $exception->getClientMessage()], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse(['status' => 'ok']);
    }
}