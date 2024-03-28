<?php

namespace App\Services\Satusehat;

use GuzzleHttp\Client;

class SatusehatAccessTokenService
{
    public static function token()
    {
        $httpClient = new Client();
        $response = $httpClient->post(SatusehatConfigService::setAuthUrl() . '/accesstoken', [
            'query' => [
                'grant_type' => 'client_credentials',
            ],
            'form_params' => [
                'client_id' => SatusehatConfigService::setClientId(),
                'client_secret' => SatusehatConfigService::setClientSecret(),
            ],
        ]);

        $data = $response->getBody()->getContents();
        $result = json_decode($data, true);
        return $result['access_token'];
    }
}
