<?php

namespace App\Service\Auth\Access;

use App\Entity\UserAccess;
use App\Service\Auth\Access\Exception\UserAccessExceededException;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;

class AccessManager
{
    const LIMIT_PER_DAY = 3;

    public function __construct(
     private EntityManagerInterface $entityManager,
        private LoggerInterface $logger,
        private string $env
    ){
    }

    private function fetchLimit(Request $request) : ?UserAccess
    {
        $ip = $request->getClientIp();
        $r = $this->entityManager->getRepository(UserAccess::class);
        $this->logger->info('Try to find existing limit', ['ip' => $ip]);

        $limit = $r->findLimitToday($ip);

        if (!$limit) {
            $this->logger->info('Limit not found');
        }

        return $limit;
    }

    public function incLimit(Request $request) : void
    {
        if ($this->env !== 'prod') {
            return;
        }

        $limit = $this->fetchLimit($request);

        if (!$limit) {
            $this->logger->info('Limit not existed. Creating.');
            $limit = (new UserAccess())
                ->setAddedAt(new \DateTime())
                ->setIp($request->getClientIp());
        }

        $limit->inc();
        $this->entityManager->persist($limit);
        $this->entityManager->flush();
    }

    public function checkAccess(Request $request) : void
    {
        $limit = $this->fetchLimit($request);

        if (!$limit) {
            return;
        }

        $this->logger->info('Limit info', [
            'ip' => $limit->getIp(),
            'added_at' => $limit->getAddedAt(),
            'current_limit' => $limit->getLimit()
        ]);

        if ($limit->getLimit() > self::LIMIT_PER_DAY) {
            $message = 'User limit exceeded.';
            $this->logger->notice($message);

            throw new UserAccessExceededException($message);
        }
    }
}