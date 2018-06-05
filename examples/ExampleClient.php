<?php

require __DIR__ . '/../vendor/autoload.php';

use Yedpay\Client;

class TestClient
{
    const STAGING = 'staging';
    const PRODUCTION = 'production';
    const ACCESS_TOKEN = 'eyJ0eXAiOi';

    /**
     * method precreate
     *
     * @param $storeId
     * @param float $amount
     * @param array $extraParam
     * @return Exception|\Yedpay\Response\Response
     */
    public function precreate($storeId, $amount = 0.1, array $extraParam = [])
    {
        try {
            //default Gateway: Alipay, HK wallet and HKD
            $client = new Client(static::STAGING, static::ACCESS_TOKEN);
            $client
                //set currency to HKD
                ->setCurrency(Client::INDEX_CURRENCY_HKD)
                //set China wallet
                ->setWallet(Client::INDEX_WALLET_CN);

            //request without extra parameters
            if (empty($extraParam)) {
                return $client->precreate($storeId, $amount);
            }

            //request with extra parameters
            return $client->precreate($storeId, $amount, json_encode($extraParam));
        } catch (Exception $e) {
            //handle the exception here
            return $e;
        }
    }
}

//mandatory parameters
$storeId = '8X4LZW2XLG9V';
$amount = 1.0;
//optional parameter: extraParam (JSON)
$extraParam = [
    'customer_name' => 'Yed Pay',
    'phone' => '12345678',
];

$testClient = new TestClient();
$precreate = $testClient->precreate($storeId, $amount, $extraParam);
var_dump($precreate);
