<?php

namespace App\Service\Checker;

use App\Form\Exception\ClientErrorAwareInterface;
use App\Service\Checker\DTO\CheckerResult;
use App\Service\Rest\Exception\InvalidResponseBodyException;
use GuzzleHttp\Exception\BadResponseException;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

class Service
{
    public function __construct(
        #[TaggedIterator('phone_checker')]
        private iterable $checkers,
        private LoggerInterface $checkerLogger,
    ){}

    public function checkPhone(string $phone) : CheckerResult
    {
        $result = new CheckerResult();

        /** @var CheckerInterface $checker */
        foreach ($this->checkers as $checker) {
            try {
                $checker->check($phone, $result);
            } catch (InvalidResponseBodyException $exception) {
                $this->checkerLogger->error('Fail parse body', [
                    'checker' => $checker::class,
                    'error' => $exception->getMessage(),
                    'body' => $exception->getBodySubstr()
                ]);
            } catch (ClientErrorAwareInterface $exception) {
                throw $exception;
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