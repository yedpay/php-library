[![Build Status](https://travis-ci.org/yedpay/php-library.svg?branch=master)](https://travis-ci.org/yedpay/php-library)
[![Coverage Status](https://coveralls.io/repos/github/yedpay/php-library/badge.svg?branch=master)](https://coveralls.io/github/yedpay/php-library?branch=master)
[![Latest Stable Version](https://poser.pugx.org/yedpay/php-library/v/stable)](https://packagist.org/packages/yedpay/php-library)
[![composer.lock](https://poser.pugx.org/yedpay/php-library/composerlock)](https://packagist.org/packages/yedpay/php-library)


# Yedpay Transaction PHP Library

## Description

A PHP library for Precreate Transactions and Online Payments Using the Yedpay API

## Prerequisites
* [Composer](https://getcomposer.org/)
* [Docker](https://www.docker.com/)
* [Yedpay](https://www.yedpay.com/)

In order to start using the API, you need to get Your Personal Access Token or API Key.

## Installation

### Installing Dependencies
    composer install
        
### Running the test
    vendor/bin/phpunit

## Integration

### if you dont use composer can autoload the library using 
    require /path/of/library/autoload.php

### Request Method

* Precreate

| Parameter | Type | Description |
| --- | --- | --- |
| accessToken | String | AccessToken used to access API |
| environment | String | Environment ( 'staging' or 'production' )|
| storeId | String | Store ID in the API|
| amount  | Double | amount of the transaction|
| currency  | Integer | transaction currency (1: HKD, 2: RMB)|
| gateway  | Integer | transaction gateway (2: UnionPay 4: Alipay Online)|
| wallet  | Integer | Alipay wallet type (1: HK, 2: CN) |
| extraParam | JSON | (Optional) Extra infomation of the transaction |
| notify_url | Url | (Optional) Notify Url of the transaction |
| return_url | Url | (Optional) Return Url of the transaction |

* Online Payment

| Parameter | Type | Description |
| --- | --- | --- |
| apiKey | String | Api Key used to access API |
| environment | String | Environment ( 'staging' or 'production' )|
| customId | String | Custom ID in the API|
| amount  | Double | amount of the transaction|
| currency  | Integer | transaction currency (1: HKD)|
| notify_url | Url | Notify Url of the transaction |
| return_url | Url | Return Url of the transaction |
| gatewayCode  | String | (Optional) transaction gateway code (4_2: Alipay Online PC2Mobile, 8_2: WeChat Pay Online) |
| wallet  | Integer | (Optional) Alipay wallet type (1: HK, 2: CN) |
| subject | String | (Optional) Product Name of the transaction |
| expiryTime | Integer | (Optional) Expiry Time in Seconds of the transaction (Range: 900 - 10800) |

* Refund

| Parameter | Type | Description |
| --- | --- | --- |
| accessToken | String | (Required without apiKey) AccessToken used to access API |
| apiKey | String | (Required without accessToken) Api Key used to access API |
| environment | String | Environment ( 'staging' or 'production' )|
| transactionId | String | Transaction ID in the API|
| reason  | String | (Optional) refund reason of the transaction|

### Result

* Success

| Parameter | Type | Description |
| --- | --- | --- |
| message | string | Response message |
| data | string(JSON) | Data of the response |

* Error

| Parameter | Type | Description |
| --- | --- | --- |
| message | string | Response message |
| errorCode | int | HTTP standard response code |
| error | string | Detail of the error |

* Exampe Precreate Transaction Implementation

Input parameters
    
    //mandatory parameters
    $accessToken = 'J84OFPAN';
    $storeId = '8X4LZW2XLG9V';
    $amount = 1.0;
    //optional parameter: extraParam (JSON)
    $extraParam = json_encode([
        'customer_name' => 'Yed Pay',
        'phone' => '12345678',
    ]);
    

Create instance of Client

    $client = new Client(Library::STAGING, $accessToken);
    
(Optional) Setting Transaction parameters

    //changing transaction currency (default: HKD)
    $client->setCurrency(Client::INDEX_CURRENCY_RMB);
    //changing alipay wallet type (default: HK)
    $client->setWallet(Client::INDEX_WALLET_CN);
    
Sending Precreate Request
    
    // general 
    $client->precreate($storeId, $amount);
    // with extra parameters
    $result = $client->precreate(
            $storeId, 
            $amount, 
            json_encode($extraParam)
    )->getData();

* Exampe Online Payment Implementation

Input parameters
    
    //mandatory parameters
    $apiKey = 'qPOcLJsNnnI2wzJdIsRULOwC//KZa+KGrarUIs1ZZa8=';
    $customId = 'test-001';
    $amount = 1.0;
    $currency = 1;
    $notifyUrl = 'https://www.example.com/notify';
    $returnUrl = 'https://www.example.com/return';
    

Create instance of Client

    $client = new Client(Library::STAGING, $apiKey, false);
    
(Optional) Setting Transaction parameters

    //changing transaction currency (default: HKD)
    $client->setCurrency(Client::INDEX_CURRENCY_HKD);
    //set transaction gateway code
    $client->setGatewayCode(Client::INDEX_GATEWAY_CODE_ALIPAY_ONLINE);
    //set transaction gateway wallet type (default: HK)
    $client->setWallet(Client::INDEX_WALLET_CN);
    //set production name of transaction
    $client->setSubject('Product');
    //set expiry time of transaction
    $client->setExpiryTime(900);
    
Sending Online Payment Request

    $client->onlinePayment($customId, $amount)->getData();

* Exampe Refund Transaction Implementation

Input parameters
    
    //mandatory parameters
    $transactionId = 'J84OFPAN';
    //optional parameter: reason (String)
    $reason = 'testing';
    

Create instance of Client

    // with accessToken
    $client = new Client(Library::STAGING, $accessToken);
    // with apiKey
    $client = new Client(Library::STAGING, $apiKey, false);
    
Sending Refund Request
    
    // general 
    $client->refund($transactionId)->getData();
    // with reason
    $client->refund($transactionId, $reason)->getData();

For the complete Code Check the examples folder: 
