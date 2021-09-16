<?php
namespace App\Services;

use App\Models\AccessToken;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class JsonRpcClient
{
    const JSON_RPC_VERSION = '2.0';

    const METHOD_URI = 'api/activity';

    protected $client;

    public function __construct()
    {
        $accessToken = AccessToken::orderBy('created_at','DESC')->first();
        $this->client = new Client([
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer '.$accessToken->access_token
            ],
            'base_uri' => config('services.data.base_uri')
        ]);
    }

    public function send(string $method, array $params = []): array
    {
        $response = $this->client
            ->post(self::METHOD_URI, [
                RequestOptions::JSON => [
                    'jsonrpc' => self::JSON_RPC_VERSION,
                    'id' => time(),
                    'method' => $method,
                    'params' => $params
                ]
            ])->getBody()->getContents();
        return json_decode($response, true);
    }
}
