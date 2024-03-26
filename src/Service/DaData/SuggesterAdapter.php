<?php

namespace App\Service\DaData;

use Dadata\DadataClient;
use Dadata\Settings;

class SuggesterAdapter
{
    private DadataClient $suggester;
    public function __construct()
    {
        $this->suggester = new DadataClient('8a5f08f3190d2d5ed7f8fb3677f74d1d05c7a0bf', null);
    }

    public function suggestAddress(string $substr) : array
    {
        $response = $this->suggester->suggest("address", $substr);

        return array_map(fn($data) => [
            'value' => $data['value'],
        ], $response);
    }

    public function suggestCity(string $substr) : array
    {
        $options = ['locations' => ['country_iso_code' => 'RU'],'from_bound' => ['value' => 'city'],'to_bound' => ['value' => 'city']];
        $response = $this->suggester->suggest("address", $substr, Settings::SUGGESTION_COUNT, $options);

        return array_map(fn($data) => [
            'value' => $data['data']['city'],
            'city' => $data['data']['city'],
            'region' => $data['data']['region'],
        ], $response);
    }

    public function suggestDepartmentCode(string $substr) : array
    {
        $response = $this->suggester->suggest("fms_unit", $substr);

        return array_map(fn($data) => [
            'value' => $data['data']['code'],
            'department' => $data['data']['name'],
        ], $response);
    }
}