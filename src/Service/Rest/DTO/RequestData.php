<?php

namespace App\Service\Rest\DTO;

use GuzzleHttp\Psr7\Request;

class RequestData
{
    private string $timestamp;

    public function __construct(private string $method, private string $url, private array $options)
    {
        $this->timestamp = microtime();
    }

    /**
     * @return string
     */
    public function getTimestamp(): string
    {
        return $this->timestamp;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }
}