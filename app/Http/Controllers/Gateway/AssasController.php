<?php

namespace App\Http\Controllers\Gateway;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use GuzzleHttp\Client;
use Carbon\Carbon;

class AssasController extends Controller {

    public function createCustomer($name, $cpfcnpj, $mobilePhone, $email) {

        $client = new Client();

        $options = [
            'headers' => [
                'Content-Type' => 'application/json',
                'access_token' => env('API_TOKEN_GATEWAY'),
                'User-Agent'   => env('APP_NAME')
            ],
            'json' => [
                'name'                  => $name,
                'cpfCnpj'               => $cpfcnpj,
                'mobilePhone'           => $mobilePhone,
                'email'                 => $email,
                'notificationDisabled'  => true
            ],
            'verify' => false
        ];

        $response = $client->post(env('API_URL_GATEWAY') . 'v3/customers', $options);
        $body = (string) $response->getBody();
        
        if ($response->getStatusCode() === 200) {
            $data = json_decode($body, true);
            return $data['id'];
        } else {
            return false;
        }
    }
    
    public function createCharge($customer, $billingType, $value, $description, $dueDate, $installments = null) {

        $client = new Client();

        $options = [
            'headers' => [
                'Content-Type' => 'application/json',
                'access_token' => env('API_TOKEN_GATEWAY'),
                'User-Agent'   => env('APP_NAME')
            ],
            'json' => [
                'customer'          => $customer,
                'billingType'       => $billingType,
                'value'             => number_format($value, 2, '.', ''),
                'dueDate'           => $dueDate,
                'description'       => $description,
                'installmentCount'  => $installments != null ? $installments : 1,
                'installmentValue'  => $installments != null ? number_format(($value / intval($installments)), 2, '.', '') : $value,
            ],
            'verify' => false
        ];

        if (env('APP_COMMISSION') == true) {
            if (!isset($options['json']['split'])) {
                $options['json']['split'] = [];
            }
        
            $options['json']['split'][] = [
                'walletId'        => env('APP_COMMISSION_WALLET'),
                'percentualValue' => number_format(env('APP_COMMISSION_VALUE'), 2, '.', '')
            ];
        }

        $response = $client->post(env('API_URL_GATEWAY') . 'v3/payments', $options);
        $body = (string) $response->getBody();

        if ($response->getStatusCode() === 200) {
            $data = json_decode($body, true);
            return $dados['json'] = [
                'id'            => $data['id'],
                'invoiceUrl'    => $data['invoiceUrl'],
            ];
        } else {
            return false;
        }
    }

    public function balance() {

        $client = new Client();

        $response = $client->request('GET',  env('API_URL_GATEWAY') . 'v3/finance/balance', [
            'headers' => [
                'accept'       => 'application/json',
                'access_token' => env('API_TOKEN_GATEWAY'),
                'User-Agent'   => env('APP_NAME')
            ],
            'verify' => false,
        ]);

        $body = (string) $response->getBody();
        if ($response->getStatusCode() === 200) {

            $data = json_decode($body, true);
            return $data['balance'];
        } else {

            return false;
        }
    }

    public function extract($startDate = null, $finishDate = null) {

        $client = new Client();

        $startDate  = $startDate ? Carbon::parse($startDate)->toDateString() : now()->subDays(10)->toDateString();
        $finishDate = $finishDate ? Carbon::parse($finishDate)->toDateString() : now()->toDateString();

        $response = $client->request('GET',  env('API_URL_GATEWAY') . "v3/financialTransactions?startDate={$startDate}&finishDate={$finishDate}&order=desc", [
            'headers' => [
                'accept'        => 'application/json',
                'access_token'  => env('API_TOKEN_GATEWAY'),
                'User-Agent'    => env('APP_NAME')
            ],
            'verify' => false,
        ]);

        $body = (string) $response->getBody();
        if ($response->getStatusCode() === 200) {
            $data = json_decode($body, true);
            return $data['data'];
        } else {
            return [];
        }
    }

    public function withdrawSend($key, $value, $type) {

        $client = new Client();
        try {
            $response = $client->request('POST', env('API_URL_GATEWAY').'v3/transfers', [
                'headers' => [
                    'accept'       => 'application/json',
                    'Content-Type' => 'application/json',
                    'access_token' => env('API_TOKEN_GATEWAY'),
                    'User-Agent'   => env('APP_NAME')
                ],
                'json' => [
                    'value'             => $value,
                    'operationType'     => 'PIX',
                    'pixAddressKey'     => $key,
                    'pixAddressKeyType' => $type,
                    'description'       => 'Saque '.env('APP_NAME'),
                ],
                'verify'  => false,
            ]);
    
            $body = $response->getBody()->getContents();
            $decodedBody = json_decode($body, true);
    
            if ($decodedBody['status'] === 'PENDING') {
                return ['success' => true, 'message' => 'Saque agendado com sucesso'];
            } else {
                return ['success' => false, 'message' => 'Situação do Saque: ' . $decodedBody['status']];
            }
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $response = $e->getResponse();
            $body = $response->getBody()->getContents();
            $decodedBody = json_decode($body, true);
    
            return ['success' => false, 'message' => $decodedBody['errors'][0]['description']];
        }
    }
}
