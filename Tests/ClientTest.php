<?php

namespace Yedpay\Tests;

use Exception;
use PHPUnit_Framework_TestCase;
use Yedpay\Client;
use Yedpay\Curl;
use Yedpay\Curl\Request;
use Yedpay\Library;
use Yedpay\Response\Error;

/**
 * Class ClientTest
 * @package Yedpay\Tests
 */
class ClientTest extends PHPUnit_Framework_TestCase
{
    private $class;
    private $mockCurl;

    public function setUp()
    {
        $this->class = new Client();
        $this->mockCurl = $this->getMockBuilder(Curl::class)
            ->setMethods(['call'])
            ->getMock();
        parent::setUp();
    }

    public function test_class_exists()
    {
        $this->assertTrue(class_exists(Client::class));
    }

    public function test_method_precreate_exists()
    {
        $this->assertTrue(method_exists(Client::class, 'precreate'));
    }

    public function test_method_get_curl_exists()
    {
        $this->assertTrue(method_exists(Client::class, 'getCurl'));
    }

    public function test_method_set_curl_exists()
    {
        $this->assertTrue(method_exists(Client::class, 'setCurl'));
    }

    public function test_method_set_currency_exists()
    {
        $this->assertTrue(method_exists(Client::class, 'setCurrency'));
    }

    public function test_method_get_currency_exists()
    {
        $this->assertTrue(method_exists(Client::class, 'getCurrency'));
    }

    public function test_method_set_wallet_exists()
    {
        $this->assertTrue(method_exists(Client::class, 'setWallet'));
    }

    public function test_method_get_wallet_exists()
    {
        $this->assertTrue(method_exists(Client::class, 'getWallet'));
    }

    public function test_client_constructor()
    {
        $client = new Client();
        $this->assertNull($client->getCurl());
        $this->assertEquals(Client::CURRENCY_HKD, $client->getCurrency());
        $this->assertEquals(Client::HK_WALLET, $client->getWallet());
        $curl = $this->createCurl();
        $client->setCurl($curl);
        $result = $client->getCurl();
        $this->assertTrue($result instanceof Curl);
        $this->assertEquals($curl, $result);
        $this->assertEquals(Client::CURRENCY_HKD, $client->getCurrency());
        $this->assertEquals(Client::HK_WALLET, $client->getWallet());
        $this->assertEquals(Client::INDEX_GATEWAY_ALIPAY, $client->getGateway());
    }

    public function test_client_constructor_with_args()
    {
        $client = new Client('production', '1234');
        $result = $client->getCurl();
        $this->assertTrue($result instanceof Curl);
        $this->assertNotNull($result);
        $this->assertEquals(Client::CURRENCY_HKD, $client->getCurrency());
        $this->assertEquals(Client::HK_WALLET, $client->getWallet());
        $this->assertEquals(Client::INDEX_GATEWAY_ALIPAY, $client->getGateway());
    }

    public function test_set_hk_wallet()
    {
        $expected = Client::HK_WALLET;
        $result = $this->class->setWallet(Client::INDEX_WALLET_HK);
        $this->assertEquals($expected, $result->getWallet());
    }

    public function test_set_cn_wallet()
    {
        $expected = Client::CN_WALLET;
        $result = $this->class->setWallet(Client::INDEX_WALLET_CN);
        $this->assertEquals($expected, $result->getWallet());
    }

    public function test_set_wallet_error()
    {
        $this->setExpectedException(Exception::class);
        $this->class->setWallet('Invalid wallet');
    }


    public function test_set_currency_hkd()
    {
        $expected = Client::CURRENCY_HKD;
        $result = $this->class->setCurrency(Client::INDEX_CURRENCY_HKD);
        $this->assertEquals($expected, $result->getCurrency());
    }

    public function test_set_currency_rmb()
    {
        $expected = Client::CURRENCY_RMB;
        $result = $this->class->setCurrency(Client::INDEX_CURRENCY_RMB);
        $this->assertEquals($expected, $result->getCurrency());
    }

    public function test_set_currency_error()
    {
        $this->setExpectedException(Exception::class);
        $this->class->setCurrency('Invalid currency');
    }

    public function test_set_gateway_alipay()
    {
        $expected = Client::INDEX_GATEWAY_ALIPAY;
        $result = $this->class->setGateway(Client::INDEX_GATEWAY_ALIPAY);
        $this->assertEquals($expected, $result->getGateway());
    }

    public function test_set_unknown_gateway()
    {
        $this->setExpectedException(Exception::class);
        $this->class->setGateway(9999);
    }

    public function test_precreate_null_curl()
    {
        $this->setExpectedException(Exception::class);
        $this->class->precreate('1234', 1);
    }

    public function test_precreate()
    {
        $mockCurl = $this->mockCurl;
        $mockCurl->method('call')->willReturn(new Error([
            'success' => false,
            'message' => 'Unauthenticated.',
            'status' => 401
        ]));
        $result = $this->class->setCurl($mockCurl)->precreate('1234', 1);
        $this->assertTrue($result instanceof Error);
    }

    public function test_precreate_with_extraparam()
    {
        $mockCurl = $this->mockCurl;
        $mockCurl->method('call')->willReturn(new Error([
            'success' => false,
            'message' => 'Unauthenticated.',
            'status' => 401
        ]));
        $result = $this->class->setCurl($mockCurl)->precreate('1234', 1, json_encode([
            'customer_name' => 'Chan Tai Man',
            'phone' => '12345678'
        ]));
        $this->assertTrue($result instanceof Error);
    }

    /**
     * @return Curl
     * @throws Exception
     */
    private function createCurl()
    {
        $curl = new Curl();
        $library = new Library();
        $library->setEnvironment('production');
        $library->setAccessToken('1234');
        $request = new Request();
        return $curl->setLibrary($library)->setRequest($request);
    }

}