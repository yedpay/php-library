<?php

//use this autoloader if not use composer
require __DIR__ . '/../autoload.php';

//use this autoloader if use composer
//require __DIR__ . '/../vendor/autoload.php';

use Yedpay\Client;
use Yedpay\Response\Success;
use Yedpay\Response\Error;

class TestClient
{
    const STAGING = 'staging';
    const PRODUCTION = 'production';
    const ACCESS_TOKEN = 'eyJ0eXAiOiJKV1QiLCJhbJSUzI1NiIsImp0aSI6IjY1NjhkOGM2MjZhNTYzMjFkNMndSH8rPBwIZn1OJK1pbi6QvQ3A4mbnNFNje9qNZJBpMFkEzEDvogSd0yvE2su_zcTfNAh8OjihNxh9vY--8EQKlqxxy_1Te1R0iahFNSOGRJYKq4vo6doDIWZoPjyT8dNKtZ7ypOpINwZ4e6Gkw4ExBTJjkrCGRvpw4s30JbgIqKrAfHUwD3O4eMLTlky1j4TQE2n0d_RGkSN1ZzZtyNYF6qI6StzUbyF_s';

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
$storeId = 'X4LZKLG9';
$amount = 0.1;
//optional parameter: extraParam (JSON)
$extraParam = [
    'customer_name' => 'Yed Pay',
    'phone' => '1234567890',
];

$testClient = new TestClient();
$precreate = $testClient->precreate($storeId, $amount, $extraParam);
// get the result
switch (true) {
    // if result instance of success can show the result
    case $precreate instanceof Success:
        $result = $precreate->getData();
        var_dump($result);
        break;

    // if result instance of error can show the error messages and error codes
    case $precreate instanceof Error:
        var_dump($precreate->getErrors());
        var_dump($precreate->getErrorCode());
        break;

    default:
        var_dump($precreate);
        break;
}
