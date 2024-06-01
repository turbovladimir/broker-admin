<?php

namespace App\Service\Auth\Access;

use App\Controller\Loan\LoanController;
use App\Entity\UserAccess;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class AccessManager
{
    const REDIRECT_BACK_URL = 'https://zaim-top-online.ru';
    const LIMIT_PER_DAY = 3;

    public function __construct(
     private EntityManagerInterface $entityManager,
        private LoggerInterface $logger,
        private RouterInterface $router,
        private string $env
    ){
    }

    private function fetchIp(Request $request) : string
    {
        return $request->headers->get('x-forwarded-for') ?? $request->getClientIp();
    }

    private function fetchLimit(Request $request) : ?UserAccess
    {
        $ip = $this->fetchIp($request);
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
                ->setIp($this->fetchIp($request));
        }

        $limit->inc();
        $this->entityManager->persist($limit);
        $this->entityManager->flush();
    }

    public function checkAccess(Request $request) : ?RedirectResponse
    {
        $limit = $this->fetchLimit($request);

        if (!$limit) {
            return null;
        }

        $this->logger->info('Limit info', [
            'ip' => $limit->getIp(),
            'added_at' => $limit->getAddedAt(),
            'current_limit' => $limit->getLimit()
        ]);

        if ($limit->getLimit() > self::LIMIT_PER_DAY) {
            $this->logger->notice('User limit exceeded.');

            return new RedirectResponse(self::REDIRECT_BACK_URL);
        }

        return null;
    }
}