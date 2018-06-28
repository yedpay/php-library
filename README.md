[![Build Status](https://travis-ci.org/yedpay/php-library.svg?branch=master)](https://travis-ci.org/yedpay/php-library)
[![Coverage Status](https://coveralls.io/repos/github/yedpay/php-library/badge.svg?branch=master)](https://coveralls.io/github/yedpay/php-library?branch=master)
[![Latest Stable Version](https://poser.pugx.org/yedpay/php-library/v/stable)](https://packagist.org/packages/yedpay/php-library)
[![composer.lock](https://poser.pugx.org/yedpay/php-library/composerlock)](https://packagist.org/packages/yedpay/php-library)


# Yedpay precreate Library

## Description

A PHP library for Precreate Transactions Using the Yedpay API

## Prerequisites
* [Composer](https://getcomposer.org/)
* [Docker](https://www.docker.com/)
* [Yedpay](https://www.yedpay.com/)

In order to start using the API, you need to get Your Personal Access Token.

## Installation

### Installing Dependencies
    composer install
        
### Running the test
    vendor/bin/phpunit

## Integration

### if you dont use composer can autoload the library using 
    require /path/of/library/autoload.php

### Request Method

| Parameter | Type | Description |
| --- | --- | --- |
| accessToken | String | AccessToken used to access API |
| environment | String | Environment ( 'staging' or 'production' )|
| storeId | String | Store ID in the API|
| amount  | double | amount of the transaction|
| currency  | integer | transaction currency (1: HKD, 2: RMB)|
| wallet  | integer | alipay wallet type (1: HK, 2: CN) |
| extraParam | JSON | Extra infomation of the transaction |

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
    $client->setCurrency(Client::INDEX_WALLET_CN);
    
Sending Precreate Request
    
    // general 
    $client->precreate($storeId, $amount);
    // with extra parameters
    $result = $client->precreate(
            $storeId, 
            $amount, 
            json_encode($extraParam)
    )->getData();

* Exampe Refund Transaction Implementation

Input parameters
    
    //mandatory parameters
    $transactionId = 'J84OFPAN';
    

Create instance of Client

    $client = new Client(Library::STAGING, $accessToken);
    
Sending Refund Request
    
    $client->refund($transactionId)->getData();

For the complete Code Check the examples folder: 
