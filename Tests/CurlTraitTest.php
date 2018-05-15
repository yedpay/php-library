<?php

namespace Yedpay\Tests;

use Yedpay\Traits\CurlTrait;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\HandlerStack;
use Psr\Http\Message\RequestInterface;

class CurlTraitTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $this->endpoint = 'http://192.168.10.11';
        $this->method = 'POST';
        $this->storeId = 'XPKRV708684M';
        $this->token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjNhYTQ1NjZiZjU4MDU4OTI5YjdiYmE4OGRkYzNjNWYxZWNmMGIyZDBiZmUxOGQ2ZTliODgwZmJkNDEwNTc1OTQ3YTkyMmQ3NjA4OTEzNTExIn0.eyJhdWQiOiIyIiwianRpIjoiM2FhNDU2NmJmNTgwNTg5MjliN2JiYTg4ZGRjM2M1ZjFlY2YwYjJkMGJmZTE4ZDZlOWI4ODBmYmQ0MTA1NzU5NDdhOTIyZDc2MDg5MTM1MTEiLCJpYXQiOjE1MTU1NzQ4OTQsIm5iZiI6MTUxNTU3NDg5NCwiZXhwIjoxNTQ3MTEwODk0LCJzdWIiOiI0ODciLCJzY29wZXMiOltdfQ.VciHSYYF5tptZyuhtbVtPQE8bW-t11N2pcg6YCdbRoMVcQuqZfjNo0FBJ5-40HgoReDsKzm6VzGSa7aZyzAEyQH5-EN6Nj03zXJDgzaL0u3PVKcNvzzRu40WFdzP6BzN0aXGEzxcDs2P8_CFmzD1GRtElzDVT8LP3M7FtB39jzsBttjCSO0o_qBlndbg71OwVpnBmd8bU9o74BYLrgfuFE8P_B-4TI_RC1dFhSLPMNmB0uUgLJZrQMKqtoOtISD1AI2fGrmvZ74Lgwl83i0WPcS-4EfKFNNzBiwLb3p4Lfs7udp6zEEKx0GYn7ghWxLImO-qo7hmkhnch8vLuOpyZaFmfIevERMxAHJEDzRzAN_E3Smk4OuH2H_zPSw3yaQVAVXTaj3mr97njKbF7w10_I73KaTGfFalVgI5GLlLAbRqzh866TutddPJkWpIXWPKwKi5m935QjVZ566UPc3DQ0ARqn81zZsemDGXG2dM-DxD1h6tz9JfximPEpBRjNZUdMvT63Sm7fZzezDV0IZrSD1GmEYJvEHJxspeil1eHrqtQts146dTmy2wPIq4zT79nNBBsOWvECVpzJ-0gEzzk1YNOK3Tn1hmw6gNIrBnSIWW4UFzSiJzah67Cy9F4DButc7Md_UoDyNBdFInQ0XlZr38hlmJssWiLtfevnFq6zM';
        $this->http = new Client(['base_uri' => $this->endpoint]);

        $this->curlTrait = CurlTrait::class;
        $this->curlMock = $this->getMockForTrait(CurlTrait::class);
    }

    protected function tearDown()
    {
        $this->endpoint = null;
        $this->method = null;
        $this->storeId = null;
        $this->token = null;
        $this->http = null;

        $this->curlTrait = null;
        $this->curlMock = null;
    }

    public function test_exist_trait()
    {
        $this->assertTrue(trait_exists($this->curlTrait));
    }

    public function test_exist_setOptionArray()
    {
        $this->assertTrue(method_exists($this->curlTrait, 'setOptionArray'));
    }

    public function test_setOptionArray()
    {
        $expected = $this->getCurl([]);

        $this->assertEquals($expected, $this->curlMock->setOptionArray('string', 'string', [], 'string'));
    }

    public function test_exist_call_method()
    {
        $this->assertTrue(method_exists($this->curlTrait, 'call'));
    }

    public function test_call_method()
    {
        $parameters = [
            'gateway_id' => 1,
            'currency' => 'HKD',
            'amount' => 0.1,
            'wallet' => 'CN',
            'extra_parameters' => '',
        ];
        $curlParameters = $this->getCurl($parameters, $this->endpoint . '/api/precreate/' . $this->storeId);

        $validator = $this->curlMock
                        ->setMethods(['call'])
                        ->getMock();

        $result = json_encode([
            'success' => true,
            'data' => [
                'id' => 'xXxXxXxXxXXxXx',
                'user_id' => 'xXxXxXxXxXXxXx',
                'company_id' => 'xXxXxXxXxXXxXx',
                'store_id' => 'xXxXxXxXxXXxXx',
                'gateway_id' => 1,
                'barcode_id' => 'xXxXxXxXxXXxXx',
                'status' => 'pending',
                'amount' => '10.00',
                'currency' => 'HKD',
                'charge' => 1.20,
                'forex' => 1,
                'paid_at' => '',
                'settled_at' => '',
                'transaction_id' => '251415799855257',
                'payer' => '',
                'extra_parameters' => '{"old_macdonald": "had a farm", "chorus_1": "E I E I O",  "and_on_his_farm": "he had a cow", "chorus_2": "E I E I O"}',
                'custom_id' => 'XxXxXxXxXxXxXxXxXx',
                'refunded_at' => '',
                'created_at' => '2018-11-20 14:06:45',
                'updated_at' => '2018-11-20 14:06:45',
                'expired_at' => '2018-11-21 14:06:45',
                '_links' => [
                    [
                        'rel' => 'checkout',
                        'href' => 'https://qr.alipay.com/0ax03657vsedczja7yru8025'
                    ],
                    [
                        'rel' => 'qrcode',
                        'href' => 'https://api.yedpay.com/v1/q/alipay/aHR0cHM6Ly9xci5hbGlwYXkuY29tL2JheDAzNjI3b3NlZHN6amE3eXJ1ODAyNA__'
                    ]
                ]
            ]
        ]);
        $validator->method('call')
                ->willReturn($result);

        // mock request / response
        $this->assertEquals($result, $this->curlMock->call($curlParameters));
    }

    // public function test_call_fail()
    // {
    //     $curlParameters = [
    //         CURLOPT_URL => 'http://192.168.10.11' . '/api/precreate/' . 'XPKRV708684M',
    //         // CURLOPT_RETURNTRANSFER => true,
    //         // CURLOPT_ENCODING => '',
    //         // CURLOPT_MAXREDIRS => 10,
    //         // CURLOPT_TIMEOUT => 30000,
    //         // CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //         CURLOPT_CUSTOMREQUEST => 'POST',
    //         // CURLOPT_POSTFIELDS => !empty($fields) ? http_build_query($fields) : null,
    //         // CURLOPT_HTTPHEADER => [
    //         //     'Authorization: Bearer ' . 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjNhYTQ1NjZiZjU4MDU4OTI5YjdiYmE4OGRkYzNjNWYxZWNmMGIyZDBiZmUxOGQ2ZTliODgwZmJkNDEwNTc1OTQ3YTkyMmQ3NjA4OTEzNTExIn0.eyJhdWQiOiIyIiwianRpIjoiM2FhNDU2NmJmNTgwNTg5MjliN2JiYTg4ZGRjM2M1ZjFlY2YwYjJkMGJmZTE4ZDZlOWI4ODBmYmQ0MTA1NzU5NDdhOTIyZDc2MDg5MTM1MTEiLCJpYXQiOjE1MTU1NzQ4OTQsIm5iZiI6MTUxNTU3NDg5NCwiZXhwIjoxNTQ3MTEwODk0LCJzdWIiOiI0ODciLCJzY29wZXMiOltdfQ.VciHSYYF5tptZyuhtbVtPQE8bW-t11N2pcg6YCdbRoMVcQuqZfjNo0FBJ5-40HgoReDsKzm6VzGSa7aZyzAEyQH5-EN6Nj03zXJDgzaL0u3PVKcNvzzRu40WFdzP6BzN0aXGEzxcDs2P8_CFmzD1GRtElzDVT8LP3M7FtB39jzsBttjCSO0o_qBlndbg71OwVpnBmd8bU9o74BYLrgfuFE8P_B-4TI_RC1dFhSLPMNmB0uUgLJZrQMKqtoOtISD1AI2fGrmvZ74Lgwl83i0WPcS-4EfKFNNzBiwLb3p4Lfs7udp6zEEKx0GYn7ghWxLImO-qo7hmkhnch8vLuOpyZaFmfIevERMxAHJEDzRzAN_E3Smk4OuH2H_zPSw3yaQVAVXTaj3mr97njKbF7w10_I73KaTGfFalVgI5GLlLAbRqzh866TutddPJkWpIXWPKwKi5m935QjVZ566UPc3DQ0ARqn81zZsemDGXG2dM-DxD1h6tz9JfximPEpBRjNZUdMvT63Sm7fZzezDV0IZrSD1GmEYJvEHJxspeil1eHrqtQts146dTmy2wPIq4zT79nNBBsOWvECVpzJ-0gEzzk1YNOK3Tn1hmw6gNIrBnSIWW4UFzSiJzah67Cy9F4DButc7Md_UoDyNBdFInQ0XlZr38hlmJssWiLtfevnFq6zM',
    //         //     'Content-Type: application/x-www-form-urlencoded',
    //         // ]
    //     ];

    //     $this->assertEquals(402, $this->curlMock->call($curlParameters));
    //     // $this->assertEquals('402 Payment Required', $this->curlMock->call($parameters));
    // }

    protected function getCurl($parameters, $endpoint = 'string')
    {
        return [
            CURLOPT_URL => $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => !empty($parameters) ? http_build_query($parameters) : null,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $this->token,
                'Content-Type: application/x-www-form-urlencoded',
            ]
        ];
    }

    // public function test_call()
    // {
    //     $storeId = '1234';
    //     $token = '4567';

    //     $parameters = [
    //         'gateway_id' => 1,
    //         'currency' => 'HKD',
    //         'amount' => 0.1,
    //         'wallet' => 'CN',
    //         'notify_url' => $this->endpoint . '/payment/notify',
    //         'extra_parameters' => '',
    //     ];

    //     // Create a mock and queue one responses.
    //     $mock = new MockHandler([
    //         new Response(402, $parameters),
    //         new RequestException('Payment Failed 交易失敗', new Request('POST', $this->endpoint . '/precreate/' . $storeId))
    //     ]);

    //     $handler = HandlerStack::create($mock);
    //     $handler->push($this->add_header('Authorization', 'Bearer ' . $token));
    //     $handler->push($this->add_header('Content-Type', 'application/x-www-form-urlencoded'));
    //     $client = new Client(['handler' => $handler]);

    //     // The first request is intercepted with the first response.
    //     $statusCode = $client->request('POST', $this->endpoint . '/precreate/' . $storeId, ['form_params' => $parameters])->getStatusCode();

    //     $this->assertEquals(402, $statusCode);
    // }

    private function add_header($header, $value)
    {
        return function (callable $handler) use ($header, $value) {
            return function (
                RequestInterface $request,
                array $options
            ) use ($handler, $header, $value) {
                $request = $request->withHeader($header, $value);
                return $handler($request, $options);
            };
        };
    }

    // public function test_exist_set_method()
    // {
    //     $this->assertTrue(method_exists(CurlTrait::class, 'setMethod'));
    // }

    // public function test_set_method()
    // {
    //     $mock = $this->getMockBuilder(CurlTrait::class)
    //         ->setMethods(['setMethod'])
    //         ->getMockForTrait();

    //     $mock->expects($this->once())
    //         ->method('setMethod')
    //         ->will($this->returnValue('string'));

    //     $this->assertEquals('string', $mock->setMethod('string'));
    // }

    // public function test_get_method()
    // {
    //     $mock = $this->getMockBuilder(CurlTrait::class)
    //         ->setMethods(['getMethod'])
    //         ->getMockForTrait();

    //     $mock->expects($this->once())
    //         ->method('getMethod')
    //         ->will($this->returnValue(true));

    //     $this->assertFalse(is_null($mock->getMethod()));
    // }
}
