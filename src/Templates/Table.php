<?php

namespace App\Templates;

class Table
{
    public function __construct(public array $head, public array $rows)
    {
    }
}