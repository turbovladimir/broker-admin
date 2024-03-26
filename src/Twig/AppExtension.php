<?php

namespace App\Twig;

use Detection\MobileDetect;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    private RequestStack $requestStack;
    private MobileDetect $mobileDetector;
    private ParameterBagInterface $bag;

    public function __construct(
        ParameterBagInterface $bag,
        RequestStack $requestStack
    ) {
        $this->requestStack = $requestStack;
        $this->mobileDetector = new MobileDetect();
        $this->bag = $bag;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('is_mobile', array($this, 'isMobile')),
            new TwigFunction('array_flip', [$this, 'arrayFlip']),
            new TwigFunction('array_shift', [$this, 'arrayShift']),
        ];
    }

    /**
     * Is mobile
     *
     * @return boolean
     */
    public function isMobile()
    {
        return $this->mobileDetector->isMobile();
    }


    public function arrayFlip(array $array) : array
    {
        return array_flip($array);
    }

    public function arrayShift(array &$array)
    {
        return array_shift($array);
    }
}