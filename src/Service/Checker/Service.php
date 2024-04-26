<?php

namespace App\Service\Checker;

use App\Service\Checker\DTO\CheckerResult;
use App\Service\Checker\LeadGid\Checker as LeadGidChecker;
use App\Service\Checker\LeadSu\Checker as LeadSuChecker;
use App\Service\Rest\Exception\InvalidResponseBodyException;
use GuzzleHttp\Exception\BadResponseException;
use Psr\Log\LoggerInterface;

class Service
{
    public function __construct(
        private LeadGidChecker $leadGidC,
        private LeadSuChecker $leadSuC,
        private LoggerInterface $checkerLogger
    ){}

    public function checkPhone(string $phone) : CheckerResult
    {
        $result = new CheckerResult();

        /** @var CheckerInterface $checker */
        foreach ([$this->leadGidC, $this->leadSuC] as $checker) {
            try {
                $checker->check($phone, $result);
            } catch (InvalidResponseBodyException $exception) {
                $this->checkerLogger->error('Fail parse body', [
                    'checker' => $checker::class,
                    'error' => $exception->getMessage(),
                    'body' => $exception->getBodySubstr()
                ]);
            } catch (BadResponseException $exception) {
                $this->checkerLogger->error('Bad response', [
                    'checker' => $checker::class,
                    'error' => $exception->getMessage(),
                ]);
            } catch (\Throwable $exception) {
                $this->checkerLogger->error('Unexpected exception', [
                    'checker' => $checker::class,
                    'error' => $exception->getMessage(),
                ]);
            }
        }

        return  $result;
    }
}