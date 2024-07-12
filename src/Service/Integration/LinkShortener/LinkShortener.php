<?php

namespace App\Service\Integration\LinkShortener;

use App\Entity\ShortLink;
use App\Repository\ShortLinkRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class LinkShortener implements LinkShortenerInterface
{
    private static $w = [
        'A', 'a', 'B', 'b', 'C', 'c', 'D', 'd', 'E', 'e', 'F', 'f', 'G', 'g', 'H', 'h', 'I', 'i',
        'J', 'j', 'K', 'k', 'L', 'l', 'M', 'm', 'N', 'n', 'O', 'o', 'P', 'p', 'Q', 'q', 'R', 'r',
        'S', 's', 'T', 't', 'U', 'u', 'V', 'v', 'W', 'w', 'X', 'x', 'Y', 'y', 'Z', 'z',
        '1', '2', '3', '4', '5', '6', '7', '8', '9', '0'
    ];

    private array $usedHashes = [];

    public function __construct(
        private EntityManagerInterface $entityManager,
        private ShortLinkRepository    $linkRepository,
        private UrlGeneratorInterface  $urlGenerator
    )
    {}

    public function shorting(string $url): string
    {
        $hash = $this->generateHash();
        $link = new ShortLink($url, $hash);

//        if ($this->linkRepository->count(['hashId' => $hash])) {
//            return $this->shorting($url);
//        }

        $this->entityManager->persist($link);

        return 'https://zmrb.ru' . $this->urlGenerator->generate('short_link_redirect', ['hash' => $hash]);
    }

    private function generateHash() : string
    {
        $hashLen = 6;
        $hash = '';
        $words = static::$w;
        $max = count($words) - 1;

        while ($hashLen) {
            $hash .= $words[rand(0, $max)];

            $hashLen--;
        }

        if (in_array($hash, $this->usedHashes)) {
            return $this->generateHash();
        }

        return $hash;
    }
}