<?php

namespace App\Twig;

use Detection\MobileDetect;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    private MobileDetect $mobileDetector;

    public function __construct() {
        $this->mobileDetector = new MobileDetect();
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