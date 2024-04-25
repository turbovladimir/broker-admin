<?php

namespace App\Service\Rest\DTO;

use Psr\Http\Message\ResponseInterface;

abstract class ResponseHandler
{
    protected ResponseInterface $response;
    abstract public function parseResponse() : array;

    public function __construct(private ResponseValidatorInterface $responseValidator)
    {
    }

    public function __invoke(ResponseInterface $response)
   {
       $this->responseValidator->validate($response);
       $this->response = $response;
   }
}