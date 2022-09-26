<?php

namespace Yedpay\Tests;

use Exception;
use Yedpay\Client;
use Yedpay\Curl;
use Yedpay\Curl\Request;
use Yedpay\Library;
use Yedpay\Response\Error;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

/**
 * Class ClientTest
 * @package Yedpay\Tests
 */
class ClientTest extends TestCase
{
    private $class;
    private $mockCurl;

    const TEST_URL = 'http:://test.com';

    public function set_up()
    {
        $this->class = new Client();
        $this->mockCurl = $this->getMockBuilder(Curl::class)
            ->setMethods(['call'])
            ->getMock();
        parent::set_up();
    }

    public function test_class_exists()
    {
        $this->assertTrue(class_exists(Client::class));
    }

    public function test_method_precreate_exists()
    {
        $this->assertTrue(method_exists(Client::class, 'precreate'));
    }

    public function test_method_refund_exists()
    {
        $this->assertTrue(method_exists(Client::class, 'refund'));
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

    public function test_method_set_return_url_exists()
    {
        $this->assertTrue(method_exists(Client::class, 'setReturnUrl'));
    }

    public function test_method_get_return_url_exists()
    {
        $this->assertTrue(method_exists(Client::class, 'getReturnUrl'));
    }

    public function test_method_set_notify_url_exists()
    {
        $this->assertTrue(method_exists(Client::class, 'setNotifyUrl'));
    }

    public function test_method_get_notify_url_exists()
    {
        $this->assertTrue(method_exists(Client::class, 'getNotifyUrl'));
    }

    public function test_set_notify_url()
    {
        $expected = static::TEST_URL;
        $result = $this->class->setNotifyUrl(static::TEST_URL);
        $this->assertEquals($expected, $result->getNotifyUrl());
    }

    public function test_set_return_url()
    {
        $expected = static::TEST_URL;
        $result = $this->class->setReturnUrl(static::TEST_URL);
        $this->assertEquals($expected, $result->getReturnUrl());
    }

    public function test_method_set_subject_exists()
    {
        $this->assertTrue(method_exists(Client::class, 'setSubject'));
    }

    public function test_method_get_subject_exists()
    {
        $this->assertTrue(method_exists(Client::class, 'getSubject'));
    }

    public function test_method_set_expiry_time_exists()
    {
        $this->assertTrue(method_exists(Client::class, 'setExpiryTime'));
    }

    public function test_method_get_expiry_time_exists()
    {
        $this->assertTrue(method_exists(Client::class, 'getExpiryTime'));
    }

    public function test_method_set_gateway_code_exists()
    {
        $this->assertTrue(method_exists(Client::class, 'setGatewayCode'));
    }

    public function test_method_get_gateway_code_exists()
    {
        $this->assertTrue(method_exists(Client::class, 'getGatewayCode'));
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
        $this->expectException(Exception::class);
        $this->class->setWallet('Invalid wallet');
    }

    public function test_set_currency_hkd()
    {
        $expected = Client::CURRENCY_HKD;
        $result = $this->class->setCurrency(Client::INDEX_CURRENCY_HKD);
        $this->assertEquals($expected, $result->getCurrency());
    }

    public function test_set_currency_error()
    {
        $this->expectException(Exception::class);
        $this->class->setCurrency('Invalid currency');
    }

    public function test_set_gateway_alipay()
    {
        $expected = Client::INDEX_GATEWAY_ALIPAY;
        $result = $this->class->setGateway(Client::INDEX_GATEWAY_ALIPAY);
        $this->assertEquals($expected, $result->getGateway());
    }

    public function test_set_gateway_alipay_online()
    {
        $expected = Client::INDEX_GATEWAY_ALIPAY_ONLINE;
        $result = $this->class->setGateway(Client::INDEX_GATEWAY_ALIPAY_ONLINE);
        $this->assertEquals($expected, $result->getGateway());
    }

    public function test_set_unknown_gateway()
    {
        $this->expectException(Exception::class);
        $this->class->setGateway(9999);
    }

    public function test_precreate_null_curl()
    {
        $this->expectException(Exception::class);
        $this->class->precreate('1234', 1);
    }

    public function test_set_gateway_code_alipay_online_wap()
    {
        $expected = Client::INDEX_GATEWAY_CODE_ALIPAY_ONLINE_WAP;
        $result = $this->class->setGatewayCode(Client::INDEX_GATEWAY_CODE_ALIPAY_ONLINE_WAP);
        $this->assertEquals($expected, $result->getGatewayCode());
    }

    public function test_set_gateway_code_alipay_online_pc2mobile()
    {
        $expected = Client::INDEX_GATEWAY_CODE_ALIPAY_ONLINE_PC2MOBILE;
        $result = $this->class->setGatewayCode(Client::INDEX_GATEWAY_CODE_ALIPAY_ONLINE_PC2MOBILE);
        $this->assertEquals($expected, $result->getGatewayCode());
    }

    public function test_set_gateway_code_wechat_pay_online()
    {
        $expected = Client::INDEX_GATEWAY_CODE_WECHATPAY_ONLINE;
        $result = $this->class->setGatewayCode(Client::INDEX_GATEWAY_CODE_WECHATPAY_ONLINE);
        $this->assertEquals($expected, $result->getGatewayCode());
    }

    public function test_set_gateway_code_unionpay_expresspay()
    {
        $expected = Client::INDEX_GATEWAY_CODE_UNIONPAY_EXPRESSPAY;
        $result = $this->class->setGatewayCode(Client::INDEX_GATEWAY_CODE_UNIONPAY_EXPRESSPAY);
        $this->assertEquals($expected, $result->getGatewayCode());
    }

    public function test_set_gateway_code_unionpay_wap()
    {
        $expected = Client::INDEX_GATEWAY_CODE_UNIONPAY_UPOP;
        $result = $this->class->setGatewayCode(Client::INDEX_GATEWAY_CODE_UNIONPAY_UPOP);
        $this->assertEquals($expected, $result->getGatewayCode());
    }

    public function test_set_gateway_code_credit_card_online()
    {
        $expected = Client::INDEX_GATEWAY_CODE_CREDIT_CARD_ONLINE;
        $result = $this->class->setGatewayCode(Client::INDEX_GATEWAY_CODE_CREDIT_CARD_ONLINE);
        $this->assertEquals($expected, $result->getGatewayCode());
    }

    public function test_set_gateway_code_unknown()
    {
        $this->expectException(Exception::class);
        $this->class->setGatewayCode(9999);
    }

    public function test_set_subject()
    {
        $expected = 'Product Name';
        $result = $this->class->setSubject('Product Name');
        $this->assertEquals($expected, $result->getSubject());
    }

    public function test_set_expiry_time()
    {
        $expected = 900;
        $result = $this->class->setExpiryTime(900);
        $this->assertEquals($expected, $result->getExpiryTime());
    }

    public function test_set_expiry_time_out_of_range()
    {
        $this->expectException(Exception::class);
        $this->class->setExpiryTime(1);
    }

    public function test_set_metadata()
    {
        $expected = json_encode([
            'woocommerce' => '1.0',
            'yedpay_for_woocommerce' => '1.0',
            'wordpress' => '1.0',
        ]);
        $result = $this->class->setMetadata($expected);
        $this->assertEquals($expected, $result->getMetadata());
    }

    public function test_set_metadata_not_array()
    {
        $this->expectException(Exception::class);
        $this->class->setMetadata('');
    }

    public function test_set_metadata_key_not_match()
    {
        $this->expectException(Exception::class);
        $this->class->setMetadata(json_encode([
            'woocommerce' => '1.0',
            'test_field' => '1.0',
        ]));
    }

    public function test_set_payment_data()
    {
        $expected = json_encode([
            'first_name' => 'Yedpay',
            'last_name' => 'Yedpay',
            'email' => 'info@example.com',
            'phone' => '12345678',
            'billing_country' => 'HK',
            'billing_city' => 'Hong Kong',
            'billing_address1' => 'Address1',
            'billing_address2' => 'Address2',
            'billing_post_code' => '000000',
        ]);
        $result = $this->class->setPaymentData($expected);
        $this->assertEquals($expected, $result->getPaymentData());
    }

    public function test_set_payment_data_not_array()
    {
        $this->expectException(Exception::class);
        $this->class->setPaymentData('');
    }

    public function test_set_payment_data_key_not_match()
    {
        $this->expectException(Exception::class);
        $this->class->setPaymentData(json_encode([
            'billing_country' => 'HK',
            'billing_city' => 'Hong Kong',
            'test_field' => '1.0',
        ]));
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

    public function test_refund_null_curl()
    {
        $this->expectException(Exception::class);
        $this->class->refund('1234', 1);
    }

    public function test_refund()
    {
        $mockCurl = $this->mockCurl;
        $mockCurl->method('call')->willReturn(new Error([
            'success' => false,
            'message' => 'Unauthenticated.',
            'status' => 401
        ]));
        $result = $this->class->setCurl($mockCurl)->refund('1234567');
        $this->assertTrue($result instanceof Error);
    }

    public function test_refund_with_reason()
    {
        $mockCurl = $this->mockCurl;
        $mockCurl->method('call')->willReturn(new Error([
            'success' => false,
            'message' => 'Unauthenticated.',
            'status' => 401
        ]));
        $result = $this->class->setCurl($mockCurl)->refund('1234567', 'test_reason');
        $this->assertTrue($result instanceof Error);
    }

    public function test_refund_with_amount()
    {
        $mockCurl = $this->mockCurl;
        $mockCurl->method('call')->willReturn(new Error([
            'success' => false,
            'message' => 'Unauthenticated.',
            'status' => 401
        ]));
        $result = $this->class->setCurl($mockCurl)->refund('1234567', null, '123.45');
        $this->assertTrue($result instanceof Error);
    }

    public function test_precreate_with_extra_param()
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

    public function test_method_online_payment_exists()
    {
        $this->assertTrue(method_exists(Client::class, 'onlinePayment'));
    }

    public function test_online_payment_null_curl()
    {
        $this->expectException(Exception::class);
        $this->class->onlinePayment('1234', 1);
    }

    public function test_online_payment()
    {
        $mockCurl = $this->mockCurl;
        $mockCurl->method('call')->willReturn(new Error([
            'success' => false,
            'message' => 'Unauthenticated.',
            'status' => 401
        ]));
        $result = $this->class->setCurl($mockCurl)->onlinePayment('1234', 1);
        $this->assertTrue($result instanceof Error);
    }

    public function test_online_payment_with_gateway_code()
    {
        $mockCurl = $this->mockCurl;
        $mockCurl->method('call')->willReturn(new Error([
            'success' => false,
            'message' => 'Unauthenticated.',
            'status' => 401
        ]));
        $result = $this->class->setGatewayCode(Client::INDEX_GATEWAY_CODE_ALIPAY_ONLINE_PC2MOBILE)
            ->setCurl($mockCurl)
            ->onlinePayment('1234', 1);
        $this->assertTrue($result instanceof Error);
    }

    public function test_online_payment_with_metadata()
    {
        $mockCurl = $this->mockCurl;
        $mockCurl->method('call')->willReturn(new Error([
            'success' => false,
            'message' => 'Unauthenticated.',
            'status' => 401
        ]));

        $result = $this->class->setMetadata(json_encode([
            'opencart' => '1.0',
            'yedpay_for_opencart' => '1.0',
        ]))
            ->setCurl($mockCurl)
            ->onlinePayment('1234', 1);
        $this->assertTrue($result instanceof Error);
    }

    public function test_method_verify_sign_exists()
    {
        $this->assertTrue(method_exists(Client::class, 'verifySign'));
    }

    public function test_verify_sign_no_sign_type()
    {
        $result = $this->class->verifySign(['1234' => 1234], '12345678901234567890123456789012');
        $this->assertFalse($result);
    }

    public function test_verify_sign_not_sha256()
    {
        $result = $this->class->verifySign(['sign' => '1234', 'sign_type' => 'RSA'], '12345678901234567890123456789012');
        $this->assertFalse($result);
    }

    public function test_verify_sign_has_unset_field()
    {
        $result = $this->class->verifySign(['sign' => '1234', 'sign_type' => 'HMAC_SHA256', 'test' => '1'], '12345678901234567890123456789012', ['test']);
        $this->assertFalse($result);
    }

    public function test_verify_sign_key_size()
    {
        $result = $this->class->verifySign(['sign' => '1234', 'sign_type' => 'HMAC_SHA256'], '1234567890');
        $this->assertFalse($result);
    }

    public function test_verify_sign()
    {
        $result = $this->class->verifySign(['sign' => '1234', 'sign_type' => 'HMAC_SHA256'], '12345678901234567890123456789012');
        $this->assertFalse($result);
    }

    public function test_method_refund_by_custom_id_exists()
    {
        $this->assertTrue(method_exists(Client::class, 'refundByCustomId'));
    }

    public function test_refund_by_custom_id_null_curl()
    {
        $this->expectException(Exception::class);
        $this->class->refundByCustomId('1234', 1);
    }

    public function test_refund_by_custom_id()
    {
        $mockCurl = $this->mockCurl;
        $mockCurl->method('call')->willReturn(new Error([
            'success' => false,
            'message' => 'Unauthenticated.',
            'status' => 401
        ]));
        $result = $this->class->setCurl($mockCurl)->refundByCustomId('1234567');
        $this->assertTrue($result instanceof Error);
    }

    public function test_refund_by_custom_id_with_reason()
    {
        $mockCurl = $this->mockCurl;
        $mockCurl->method('call')->willReturn(new Error([
            'success' => false,
            'message' => 'Unauthenticated.',
            'status' => 401
        ]));
        $result = $this->class->setCurl($mockCurl)->refundByCustomId('1234567', 'test_reason');
        $this->assertTrue($result instanceof Error);
    }

    public function test_refund_by_custom_id_with_amount()
    {
        $mockCurl = $this->mockCurl;
        $mockCurl->method('call')->willReturn(new Error([
            'success' => false,
            'message' => 'Unauthenticated.',
            'status' => 401
        ]));
        $result = $this->class->setCurl($mockCurl)->refundByCustomId('1234567', null, '123.45');
        $this->assertTrue($result instanceof Error);
    }

    public function test_set_refund_parameters()
    {
        $result = $this->class->setRefundParameters('test_reason', '123.00', 'test_custom_id');
        $this->assertTrue($result['refund_reason'] == 'test_reason');
        $this->assertTrue($result['amount'] == '123.00');
        $this->assertTrue($result['custom_id'] == 'test_custom_id');
    }

    public function test_set_refund_parameters_with_null_reason()
    {
        $result = $this->class->setRefundParameters(null, '123.00', 'test_custom_id');
        $this->assertTrue(!isset($result['refund_reason']));
    }

    public function test_set_refund_parameters_with_null_amount()
    {
        $result = $this->class->setRefundParameters('test_reason', null, 'test_custom_id');
        $this->assertTrue(!isset($result['amount']));
    }

    public function test_set_refund_parameters_with_null_custom_id()
    {
        $result = $this->class->setRefundParameters('test_reason', '123.00', null);
        $this->assertTrue(!isset($result['custom_id']));
    }

    public function test_set_refund_parameters_with_non_numeric_amount()
    {
        $this->expectException(Exception::class);
        $this->class->setRefundParameters(null, 'test', null);
    }

    public function test_method_set_checkout_domain_id_exists()
    {
        $this->assertTrue(method_exists(Client::class, 'setCheckoutDomainId'));
    }

    public function test_set_checkout_domain_id()
    {
        $expected = '123456';
        $result = $this->class->setCheckoutDomainId('123456');
        $this->assertEquals($expected, $result->getCheckoutDomainId());
    }

    public function test_method_query_online_payment_exists()
    {
        $this->assertTrue(method_exists(Client::class, 'queryOnlinePayment'));
    }

    public function test_query_online_payment_null_curl()
    {
        $this->expectException(Exception::class);
        $this->class->queryOnlinePayment('1234');
    }

    public function test_query_online_payment()
    {
        $mockCurl = $this->mockCurl;
        $mockCurl->method('call')->willReturn(new Error([
            'success' => false,
            'message' => 'Unauthenticated.',
            'status' => 401
        ]));
        $result = $this->class->setCurl($mockCurl)->queryOnlinePayment('1234567');
        $this->assertTrue($result instanceof Error);
    }
}
