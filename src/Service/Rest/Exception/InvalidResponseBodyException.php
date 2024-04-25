<?php

namespace App\Service\Rest\Exception;

class InvalidResponseBodyException extends \Exception
{
    private string $bodySubstr;
    public function __construct(string $body)
    {
        $this->bodySubstr = substr($body, 0, 30);
        parent::__construct("Response body contains errors.");
    }


    public function getBodySubstr() : string
    {
        return $this->bodySubstr;
    }
}