<?php

//use this autoloader if not use composer
require __DIR__ . '/../autoload.php';

//use this autoloader if use composer
//require __DIR__ . '/../vendor/autoload.php';

use Yedpay\Client;

class TestClient
{
    const STAGING = 'staging';
    const PRODUCTION = 'production';
    const ACCESS_TOKEN = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjY1NThjMzJmMjhkOGM2MjZhNTYzMjFkNmVhMzEwMzgyN2MxMjlkZTA1YWQ3YWEyZmE1MTM5OTdiNGIyZmZiODFkZWE3MGYzMWQwNmIwNjEyIn0.eyJhdWQiOiI1IiwianRpIjoiNjU1OGMzMmYyOGQ4YzYyNmE1NjMyMWQ2ZWEzMTAzODI3YzEyOWRlMDVhZDdhYTJmYTUxMzk5N2I0YjJmZmI4MWRlYTcwZjMxZDA2YjA2MTIiLCJpYXQiOjE1MjEwMjM1NTAsIm5iZiI6MTUyMTAyMzU1MCwiZXhwIjoxNTUyNTU5NTUwLCJzdWIiOiIyIiwic2NvcGVzIjpbXX0.RqsgMp7DIjh73ZRAAEUjq3zSnGCAuaJre09b67edk8sl8Hf9KvMDTWngBltXWtHeo0zvv1HlWJjsDsdkUZ_AJHfULCwGSomKp1-z9xbHVVB0gnkv9hTb8Fuvvi7sXmUFMjemTyvuVzKX5BP2AafSWaFz0u2teIiJK3EFq57e9DrmsyZb47B713cIICIJWIcc6tEKj_8LGwLA6U6BpEBSZAr5pS1oZUk7dv03jZczo-AWa7yv9VSVMfqDJtn-1ZXMxFt5FXgzxeqEuT6gE4k1gsggr3ux5jvvn7Rbrfhf2XVhpVWyHDnIEtpLEUqshkLCmfcwNwf-Tmga7tcu7Zv-0TPrIT1qGgeF26nUhN4WCeFxSoQwJXV_2IA6ehp7s-_rvqPCDq-4SW0-XNya9Go0w1Zk4arPAtoIsemSftK3_F-NRfMHn8QDM483_OEQWgJ-mPHxIXHEndSH8rPBwIZn1OJK1pbi6QvQ3A4mbnNFNje9qNZJBpMFkEzEDvogSd0yvE2su_zcTfNAh8OjihNxh9vY--8EQKlqxxy_1Te1R0iahFNSOGRJYKq4vo6doDIWZoPjyT8dNKtZ7ypOpINwZ4e6Gkw4ExBTJjkrCGRvpw4s30JbgIqKrAfHUwD3O4eMLTlky1j4TQE2n0d_RGkSN1ZzZtyNYF6qI6StzUbyF_s';

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
    'phone' => 'asdasdasdasdas',
];

$testClient = new TestClient();
$precreate = $testClient->precreate($storeId, $amount, $extraParam);
var_dump($precreate);
