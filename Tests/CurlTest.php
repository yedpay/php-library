<?php

namespace Yedpay\Tests;

use Yedpay\Curl;
use Yedpay\Library;
use Yedpay\Curl\Request;
use Exception;
use Yedpay\Response\Error;
use Yedpay\Response\Success;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

/**
 * Class CurlTest
 * @package Yedpay\Tests
 */
class CurlTest extends TestCase
{
    protected $endpoint;
    protected $path;
    protected $token;
    protected $parameters;
    protected $method;

    protected $class;
    protected $library;
    protected $http;
    protected $successHttp;
    protected $errorResponseHttp;
    protected $curlErrorHttp;
    protected $request;

    const SUCCESS = 'success';
    const FAIL = 'fail';
    const ERROR = 'error';
    const WRONGFORMAT = 'wrongFormat';
    const CURLERROR = 'curlError';

    public function set_up()
    {
        $this->endpoint = 'https://api-staging.yedpay.com';
        $this->path = '/precreate/7V57';
        $this->token = 'abcdefg';
        $this->parameters = [
                'gateway_id' => 1,
                'currency' => 'HKD',
                'amount' => 0.1,
                'wallet' => 'CN',
            ];
        $this->method = 'POST';

        $this->class = new Curl();
        $this->library = $this->mockLibrary();
        $this->http = $this->mockHttp();
        $this->successHttp = $this->mockSuccess();
        $this->curlErrorHttp = $this->mockCurlError();
        $this->wrongFormatHttp = $this->mockWrongFormatResponse();
        $this->request = $this->getMockBuilder(Request::class)
                        ->setMethods(['setOptionArray', 'execute', 'error', 'close'])
                        ->getMock();
    }

    public function tear_down()
    {
        $this->endpoint = null;
        $this->path = null;
        $this->token = null;
        $this->parameters = null;
        $this->method = null;

        $this->class = null;
        $this->library = null;
        $this->http = null;
        $this->request = null;
    }

    public function test_exist_class()
    {
        $this->assertTrue($this->class instanceof Curl);
    }

    public function test_exist_method_call()
    {
        $this->assertTrue(method_exists($this->class, 'call'));
    }

    public function test_call_success()
    {
        $request = $this->request;
        $request->method('execute')
            ->willReturn($this->successHttp->execute());

        $actual = $this->class
            ->setLibrary($this->library)
            ->setRequest($request)
            ->call($this->path, $this->method, $this->parameters);
        $this->assertTrue($actual instanceof Success);
    }

    public function test_call_fail()
    {
        $request = $this->request;
        $request->method('execute')
            ->willReturn($this->http->execute());

        $actual = $this->class
            ->setLibrary($this->library)
            ->setRequest($request)
            ->call($this->path, $this->method, $this->parameters);
        $this->assertTrue($actual instanceof Error);
    }

    public function test_call_curl_error()
    {
        $request = $this->request;
        $request->method('execute')
            ->willReturn($this->curlErrorHttp->execute());
        $request->method('error')
            ->willReturn($this->curlErrorHttp->error());

        $actual = $this->class
            ->setLibrary($this->library)
            ->setRequest($request)
            ->call($this->path, $this->method, $this->parameters);
        $this->assertTrue($actual instanceof Error);
    }

    public function test_call_error()
    {
        $request = $this->request;
        $request->method('error')
            ->willReturn($this->http->error());

        $actual = $this->class
            ->setLibrary($this->library)
            ->setRequest($request)
            ->call($this->path, $this->method, $this->parameters);
        $this->assertTrue($actual instanceof Error);
    }

    public function test_call_exception()
    {
        $request = $this->request;
        $request->method('execute')
                ->will($this->throwException(new Exception('string')));

        $actual = $this->class
                ->setLibrary($this->library)
                ->setRequest($request)
                ->call($this->path, $this->method, $this->parameters);

        $this->assertTrue($actual instanceof Error);
    }

    public function test_call_error_response()
    {
        $request = $this->request;
        $request->method('execute')
            ->willReturn($this->wrongFormatHttp->execute());
        $actual = $this->class
                ->setLibrary($this->library)
                ->setRequest($request)
                ->call($this->path, $this->method, $this->parameters);

        $this->assertTrue($actual instanceof Error);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function mockLibrary()
    {
        $library = $this->getMockBuilder(Library::class)
                        ->setMethods(['getEndpoint', 'getAccessToken'])
                        ->getMock();
        $library->method('getEndpoint')
            ->willReturn($this->endpoint);
        $library->method('getAccessToken')
            ->willReturn($this->token);
        return $library;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function mockHttp()
    {
        $http = $this->getMockBuilder('HttpRequest')
                        ->setMethods(['execute', 'error'])
                        ->getMock();
        $http->method('execute')
            ->willReturn($this->getTestResult(static::FAIL));
        $http->method('error')
            ->willReturn($this->getTestResult(static::ERROR));
        return $http;
    }

    protected function mockSuccess()
    {
        $http = $this->getMockBuilder('HttpRequest')
            ->setMethods(['execute', 'error'])
            ->getMock();
        $http->method('execute')
            ->willReturn($this->getTestResult(static::SUCCESS));
        $http->method('error')
            ->willReturn(null);
        return $http;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function mockWrongFormatResponse()
    {
        $http = $this->getMockBuilder('HttpRequest')
            ->setMethods(['execute', 'error'])
            ->getMock();
        $http->method('execute')
            ->willReturn($this->getTestResult(static::WRONGFORMAT));
        $http->method('error')
            ->willReturn(null);
        return $http;
    }

    private function mockCurlError()
    {
        $http = $this->getMockBuilder('HttpRequest')
            ->setMethods(['execute', 'error'])
            ->getMock();
        $http->method('execute')
            ->willReturn(null);
        $http->method('error')
            ->willReturn($this->getTestResult(static::CURLERROR));
        return $http;
    }

    /**
     * @param $type
     * @return string
     */
    protected function getTestResult($type)
    {
        switch ($type) {
            case static::SUCCESS:
                return json_encode([
                    'success' => true,
                    'data' => [
                        'id' => '5YMRN2MM62GP',
                        'user_id' => 'PK80L77DLGNW',
                        'company_id' => 'D5JQLX2M694E',
                        'store_id' => 'YVGREM1E650O',
                        'gateway_id' => 1,
                        'barcode_id' => '5Z4ROO28RJVY',
                        'status' => 'pending',
                        'amount' => '0.10',
                        'currency' => 'HKD',
                        'charge' => '0.00',
                        'net' => '0.10',
                        'forex' => 1,
                        'paid_at' => '',
                        'settled_at' => '',
                        'transaction_id' => '152664013548796',
                        'payer' => '',
                        'extra_parameters' => '',
                        'custom_id' => '',
                        'refunded_at' => '',
                        'created_at' => '2018-05-18 18 =>42 =>16',
                        'updated_at' => '2018-05-18 18 =>42 =>16',
                        'expired_at' => '2018-05-19 18 =>42 =>16',
                        '_links' => [
                            [
                                'rel' => 'checkout',
                                'href' => 'https =>//qr.alipay.com/bax00186h9gdkvlumpwo409f'
                            ],
                            [
                                'rel' => 'qrcode',
                                'href' => 'https =>//dev2.yedpay.com/q/alipay/aHR0cHM6Ly9xci5hbGlwYXkuY29tL2JheDAwMTg2aDlnZGt2bHVtcHdvNDA5Zg__'
                            ]
                        ]
                    ]
                ]);
            case static::FAIL:
                return json_encode([
                    'success' => false,
                    'message' => 'Unauthenticated.',
                    'status' => 401
                ]);
            case static::ERROR:
                return 'http_build_query(): Parameter 1 expected to be Array or Object.  Incorrect value given';
            case static::WRONGFORMAT:
                return 'This is not a Json';
            case static::CURLERROR:
                return 'SSL certificate problem: unable to get local issuer certificate';
        }
    }
}
