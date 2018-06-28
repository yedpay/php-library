<?php

//use this autoloader if not use composer
require __DIR__ . '/../autoload.php';

//use this autoloader if use composer
//require __DIR__ . '/../vendor/autoload.php';

use Yedpay\Client;
use Yedpay\Response\Success;
use Yedpay\Response\Error;

class TestRefundClient
{
    const STAGING = 'staging';
    const PRODUCTION = 'production';
    const ACCESS_TOKEN = 'eyJ0eXAiOiJKV1QiLCJhbJSUzI1NiIsImp0aSI6IjY1NjhkOGM2MjZhNTYzMjFkNMndSH8rPBwIZn1OJK1pbi6QvQ3A4mbnNFNje9qNZJBpMFkEzEDvogSd0yvE2su_zcTfNAh8OjihNxh9vY--8EQKlqxxy_1Te1R0iahFNSOGRJYKq4vo6doDIWZoPjyT8dNKtZ7ypOpINwZ4e6Gkw4ExBTJjkrCGRvpw4s30JbgIqKrAfHUwD3O4eMLTlky1j4TQE2n0d_RGkSN1ZzZtyNYF6qI6StzUbyF_s';

    /**
     * method refund
     *
     * @param $storeId
     * @param float $amount
     * @param array $extraParam
     * @return Exception|\Yedpay\Response\Response
     */
    public function refund($transactionId)
    {
        try {
            $client = new Client(static::STAGING, static::ACCESS_TOKEN);
            return $client->refund($transactionId);
        } catch (Exception $e) {
            //handle the exception here
            return $e;
        }
    }
}

//mandatory parameters
$transactionId = 'X4LZKLG9';

$testRefundClient = new TestRefundClient();
$refund = $testRefundClient->refund($transactionId);
// get the result
switch (true) {
    // if result instance of success can show the result
    case $refund instanceof Success:
        $result = $refund->getData();
        var_dump($result);
        break;

    // if result instance of error can show the error messages and error codes
    case $precreate instanceof Error:
        var_dump($refund->getErrors());
        var_dump($refund->getErrorCode());
        break;

    default:
        var_dump($refund);
        break;
}
