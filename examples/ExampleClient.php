<?php

require __DIR__ . '/../vendor/autoload.php';

use Yedpay\Client;
use Yedpay\Library;

$accessToken = 'eyJ0eXAiOi';
try {
    //default Gateway: Alipay, HK wallet and HKD
    $client = new Client(Library::STAGING, $accessToken);
    $client->setCurrency(Client::INDEX_CURRENCY_RMB)//set currency to RMB
    ->setWallet(Client::INDEX_WALLET_CN);//set China wallet

    //mandatory parameters
    $storeId = '8X4LZW2XLG9V';
    $amount = 1.0;
    //optional parameter: extraParam (JSON)
    $extraParam = json_encode([
        'customer_name' => 'Yed Pay',
        'phone' => '12345678',
    ]);
    $result = $client->precreate($storeId, $amount, $extraParam);

    $result = $client->precreate(
        'UH9fjfp9',
        1.0,
        json_encode([
            'customer_name' => 'Yed Pay',
            'phone' => '12345678',
        ])
    );
    var_dump($result);
} catch (Exception $e) {
    var_dump($e);
}