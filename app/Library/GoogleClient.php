<?php

namespace App\Library;

use Google\Client;
use Google\Service\Oauth2 as ServiceOauth2;
use Google\Service\Oauth2\Userinfo;
use Google\Service\Drive as GoogleDrive;
use Google\Service\Sheets as GoogleSheets;

class GoogleClient {

    private Userinfo $data;
    public readonly Client $client;

    public function __construct() {
        $this->client = new Client();
    }

    public function init() {
        $guzzleClient = new \GuzzleHttp\Client(['curl' => [CURLOPT_SSL_VERIFYPEER => false, ]]);
        $this->client->setHttpClient($guzzleClient);
        $this->client->setClientId($_ENV['GOOGLE_CLIENT_ID']);
        $this->client->setClientSecret($_ENV['GOOGLE_CLIENT_SECRET']);
        $this->client->setRedirectUri(env('APP_URL').'login');
        $this->client->addScope('email');
        $this->client->addScope('profile');
        $this->client->addScope(GoogleDrive::DRIVE);
        $this->client->addScope(GoogleSheets::SPREADSHEETS);
    }

    public function authenticated() {
        if (isset($_GET['code'])) {
            $token = $this->client->fetchAccessTokenWithAuthCode($_GET['code']);
            $this->client->setAccessToken($token['access_token']);
            $google_service = new ServiceOauth2($this->client);
            $this->data = $google_service->userinfo->get();

            session(['google_access_token' => $token]);
            return true;
        }

        return false;
    }

    public function getData() {
        return $this->data;
    }

    public function generateLink() {
        return $this->client->createAuthUrl();
    }

    public function getDriveService() {
        $this->checkTokenExpiration();
        return new GoogleDrive($this->client);
    }

    public function getSheetsService() {
        $this->checkTokenExpiration();
        return new GoogleSheets($this->client);
    }

    private function checkTokenExpiration() {
        if ($this->client->isAccessTokenExpired()) {
            $refreshToken = session('google_access_token')['refresh_token'] ?? null;

            if ($refreshToken) {
                // Atualiza o token de acesso
                $this->client->fetchAccessTokenWithRefreshToken($refreshToken);
                session(['google_access_token' => $this->client->getAccessToken()]);
            } else {
                // Redireciona para o login se o token estiver expirado e sem refresh token
                redirect($this->generateLink())->send();
            }
        }
    }
}