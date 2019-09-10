<?php

use Yedpay\Client;
use Yedpay\Response\Success;
use Yedpay\Response\Error;

class TestOnlinePaymentClient
{
    const STAGING = 'staging';
    const PRODUCTION = 'production';
    const API_KEY = 'qPOcLJsNnnI2wzJdIsRULOwC//KZa+KGrarUIs1ZZa8=';
    const NOTIFY_URL = 'https://api-staging.yedpay.com/notify/alipay-online';
    const RETURN_URL = 'https://api-staging.yedpay.com/';
 
    /**
     * method onlinePayment
     *
     * @param $customId
     * @param float $amount
     * @return Exception|\Yedpay\Response\Response
     */
    public function onlinePayment($customId, $amount = 0.1)
    {
        try {
            //default Gateway: Alipay, HK wallet and HKD
            $client = new Client(static::STAGING, static::API_KEY);
            $client
                //set currency to HKD
                ->setCurrency(Client::INDEX_CURRENCY_HKD)
                //set notify url
                ->setNotifyUrl(static::NOTIFY_URL)
                //set return url
                ->setReturnUrl(static::RETURN_URL);

            //request
            return $client->onlinePayment($customId, $amount);
        } catch (Exception $e) {
            //handle the exception here
            return $e;
        }
    }
}

//mandatory parameters
$customId = substr(md5(rand()), 0, 20);
$amount = 0.1;

$testOnlinePaymentClient = new TestOnlinePaymentClient();
$onlinePayment = $testOnlinePaymentClient->onlinePayment($customId, $amount);
// get the result
switch (true) {
    // if result instance of success can show the result
    case $onlinePayment instanceof Success:
        $result = $onlinePayment->getData();
        var_dump($result);
        break;

    // if result instance of error can show the error messages and error codes
    case $onlinePayment instanceof Error:
        var_dump($onlinePayment->getErrors());
        var_dump($onlinePayment->getErrorCode());
        break;

    default:
        var_dump($onlinePayment);
        break;
}
