<?php

namespace App\Service\Checker;

use App\Service\Checker\DTO\CheckerResult;

interface CheckerInterface
{
    public function check(string $phone, CheckerResult $result) : void;
}