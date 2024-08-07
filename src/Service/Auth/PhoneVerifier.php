<?php

namespace App\Service\Auth;

use App\Controller\Session;
use App\Entity\Contact;
use App\Entity\PhoneVerifyJob;
use App\Repository\PhoneVerifyJobRepository;
use App\Service\Auth\DTO\VerifyCodeRequest;
use App\Service\Auth\Exception\PhoneVerify\ExpiredCodeException;
use App\Service\Auth\Exception\PhoneVerify\NotFoundCodeException;
use App\Service\Sms\Sender;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class PhoneVerifier
{
    public function __construct(
        private Sender                 $smsSender,
        private EntityManagerInterface $entityManager,
        private PhoneVerifyJobRepository $phoneVerifyJobRepository,
        private LoggerInterface        $smsLogger,
        private string                 $env
    ){}

    public function sendCode(string $sessionId, string $phone) : void
    {
        try {
            $existJob = $this->entityManager
                ->getRepository(PhoneVerifyJob::class)->findOneBy(['sessionId' => $sessionId, 'isActive' => true]);

            if ($existJob) {
                $this->entityManager->persist($existJob->setIsActive(false));
            }

            $job = (new PhoneVerifyJob())
                ->setAddedAt(new \DateTime())
                ->setIsActive(true)
                ->setIsVerified(false)
                ->setPhone($phone)
                ->setCode($this->generateCode())
                ->setSessionId($sessionId)
            ;

            if ($this->env === 'prod') {
                $phones = array_map(fn($phone) => $phone, [$job->getPhone()]) ;
                $message = sprintf('Ваш проверочный код: %d', $job->getCode());
                $this->smsSender->send($phones, $message);
            }

            $this->entityManager->persist($job);
            $this->entityManager->flush();
        } catch (\Throwable $e) {
            $this->smsLogger->error('Error occurring during send phone', ['exception', $e->getMessage()]);

            throw $e;
        }
    }

    public function verify(VerifyCodeRequest $request) : bool
    {
        $s = $request->getSession();

        /** @var PhoneVerifyJob $job */
        $job = $this->phoneVerifyJobRepository->findActiveJob(
            $s->getId(),
            $request->getCode(),
            $request->getPhone()
        );

        if (!$job) {
            throw new NotFoundCodeException();
        }

        if ($request->getTime()->modify('-5 min') > $job->getAddedAt()) {
            throw new ExpiredCodeException();
        }

        $this->entityManager->persist($job->setIsVerified(true));
        $c = new Contact($job->getPhone());
        $this->entityManager->persist($c);
        $this->entityManager->flush();

        $s->set(Session::PhoneVerified->value, true);
        $s->set(Session::ContactHash->value, $c->getContactId());

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