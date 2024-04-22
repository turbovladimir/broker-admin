<?php

namespace App\Service\Auth;

use App\Entity\PhoneVerifyJob;
use App\Service\Auth\DTO\SendCodeRequest;
use App\Service\Auth\DTO\VerifyCodeRequest;
use App\Service\Auth\Exception\PhoneVerify\ExpiredCodeException;
use App\Service\Auth\Exception\PhoneVerify\NotFoundCodeException;
use App\Service\Sms\DTO\SendSmsRequest;
use App\Service\Sms\Sender;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class PhoneVerifier
{
    public function __construct(
        private Sender $smsSender,
        private EntityManagerInterface $entityManager,
        private LoggerInterface $phoneVerifyLogger,
        private string $env
    )
    {
    }

    public function sendCode(SendCodeRequest $request) : void
    {
        try {
            $sId = $request->getSessionId();

            $existJob = $this->entityManager
                ->getRepository(PhoneVerifyJob::class)->findOneBy(['sessionId' => $sId, 'isActive' => true]);

            if ($existJob) {
                $this->entityManager->persist($existJob->setIsActive(false));
            }

            $job = (new PhoneVerifyJob())
                ->setAddedAt(new \DateTime())
                ->setIsActive(true)
                ->setIsVerified(false)
                ->setPhone($request->getPhone())
                ->setCode($this->generateCode())
                ->setSessionId($sId)
            ;

            if ($this->env === 'prod') {
                $message = sprintf('Ваш проверочный код: %d', $job->getCode());
                $this->smsSender->send(new SendSmsRequest([$job->getPhone()], $message));
            }

            $this->entityManager->persist($job);
            $this->entityManager->flush();
        } catch (\Throwable $e) {
            $this->phoneVerifyLogger->error('Error occurring during send phone', ['exception', $e->getMessage()]);

            throw $e;
        }
    }

    public function verify(VerifyCodeRequest $request) : bool
    {
        /** @var PhoneVerifyJob $job */
        $job = $this->entityManager->getRepository(PhoneVerifyJob::class)->findActiveJob($request);

        if (!$job) {
            throw new NotFoundCodeException();
        }

        if ($request->getTime()->modify('-5 min') > $job->getAddedAt()) {
            throw new ExpiredCodeException();
        }

        $this->entityManager->persist($job->setIsVerified(true));
        $this->entityManager->flush();

        return true;
    }

    private function generateCode() : int
    {
        if ($this->env === 'prod') {
            return random_int(1000, 9999);
        }

        return 7777;
    }
}