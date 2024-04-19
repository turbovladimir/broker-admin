<?php

namespace App\Service\DaData;

use Dadata\DadataClient;
use Dadata\Settings;
use GuzzleHttp\Exception\ConnectException;
use Psr\Log\LoggerInterface;

class SuggesterAdapter
{
    private DadataClient $suggester;
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->suggester = new DadataClient('8a5f08f3190d2d5ed7f8fb3677f74d1d05c7a0bf', null);
        $this->logger = $logger;
    }

    public function suggestAddress(string $substr) : array
    {
        $response = $this->suggest("address", $substr);

        return array_map(fn($data) => [
            'value' => $data['value'],
        ], $response);
    }

    public function suggestCity(string $substr) : array
    {
        $options = ['locations' => ['country_iso_code' => 'RU'],'from_bound' => ['value' => 'city'],'to_bound' => ['value' => 'city']];
        $response = $this->suggest("address", $substr, Settings::SUGGESTION_COUNT, $options);

        return array_map(fn($data) => [
            'value' => $data['data']['city'],
            'city' => $data['data']['city'],
            'region' => $data['data']['region'],
        ], $response);
    }

    public function suggestDepartmentCode(string $substr) : array
    {
        $response = $this->suggest("fms_unit", $substr);

        return array_map(fn($data) => [
            'value' => $data['data']['code'],
            'department' => $data['data']['name'],
        ], $response);
    }

    private function suggest($name, $query, $count = Settings::SUGGESTION_COUNT, $kwargs = [])
    {
        try {
            return $this->suggester->suggest($name, $query, $count, $kwargs);
        } catch (ConnectException $exception) {
            $this->logger->warning('Suggest fail.', [
                'message' => $exception->getMessage(),
                'suggest_namespace' => $name,
                'query' => $query
            ]);
        }

        return [];
    }
}