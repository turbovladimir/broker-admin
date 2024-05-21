<?php

namespace App\Service\Checker\Double;

use App\Repository\LoanRequestRepository;
use App\Service\Checker\CheckerInterface;
use App\Service\Checker\DTO\CheckerResult;

class Checker implements CheckerInterface
{
    public function __construct(private LoanRequestRepository $loanRequestRepository)
    {
    }

    public function check(string $phone, CheckerResult $result): void
    {
        $requests = $this->loanRequestRepository->findBy(['phone' => '+7' . $phone], ['addedAt' => 'DESC']);

        if (empty($requests)) {
            return;
        }

        $lastRequest = $requests[0];

        if ($lastRequest->getAddedAt() > (new \DateTime())->modify('-3 days')) {
            throw new DupePhoneException();
        }
    }
}