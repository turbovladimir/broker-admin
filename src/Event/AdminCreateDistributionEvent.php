<?php

namespace App\Event;

use App\Entity\SmsQueue;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\Event;

class AdminCreateDistributionEvent extends Event
{
    public function __construct(public readonly Request $request, public readonly SmsQueue $queue)
    {
    }
}