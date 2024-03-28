<?php

namespace App\Services\Satusehat;

use GuzzleHttp\Client;

class SatusehatPractitionerService
{
    protected $httpClient;
    protected $accessToken;
    protected $config;

    public function __construct()
    {
        $this->httpClient = new Client();
        $this->accessToken = new SatusehatAccessTokenService();
        $this->config = new SatusehatConfigService();
    }

    protected function processParams($params)
    {

        if (isset($params['identifier'])) {
            $params['identifier'] = 'https://fhir.kemkes.go.id/id/nik|' . $params['identifier'];
        }
        if (isset($params['name'])) {
            $params['name'] = $params['name'];
        }

        if (isset($params['birthdate'])) {
            $params['birthdate'] = $params['birthdate'];
        }

        return $params;
    }

    public function getRequest($endpoint, $params = [])
    {
        $token = $this->accessToken->token();

        $url = $this->config->setUrl() . $endpoint;

        $params = $this->processParams($params);

        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }

        $response = $this->httpClient->get($url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
            ],
        ]);

        $data = $response->getBody()->getContents();

        return json_decode($data, true);
    }
}
