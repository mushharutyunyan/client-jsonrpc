<?php

namespace App\Services;

use App\Models\AccessToken;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class AuthClient
{
    const METHOD_URI = '/oauth/token';

    public function check(): array
    {
        $lastToken = AccessToken::orderBy('created_at','DESC')->first();
        if(!$lastToken) {
            return $this->send();
        }
        $tokenExpiredTime = Carbon::parse($lastToken->created_at)->addSeconds($lastToken->expired_in);
        if($tokenExpiredTime->greaterThan(Carbon::now())) {
            $response = $lastToken->toArray();
            $response['error'] = false;
        } else {
            return $this->send();
        }

        return $response;
    }

    public function send(): array
    {
        $response = Http::post(env('SERVER_URL') . self::METHOD_URI, [
            'client_id' => env('CLIENT_ID'),
            'client_secret' => env('CLIENT_SECRET'),
            'grant_type' => 'password',
            'username' => env('CLIENT_USERNAME'),
            'password' => env('CLIENT_PASSWORD')
        ]);
        $error = false;
        if(!$response->ok()) {
            $error = true;
        }
        $response = json_decode($response, true);
        AccessToken::create($response);
        $response['error'] = $error;
        return $response;
    }
}
