<?php

namespace Yedpay;

use Exception;
use Yedpay\Curl\Request;

/**
 * Class Client
 * @package Yedpay
 */
class Client
{
    private $gateway = 1;
    private $curl = null;
    private $returnUrl = null;
    private $notifyUrl = null;
    private $path;
    private $currency;
    private $wallet;
    private $subject;
    private $expiryTime;
    private $gatewayCode;
    private $metadata;
    private $paymentData;
    private $checkoutDomainId;

    private $precreatePath = '/precreate/%s';
    private $onlinePaymentPath = '/online-payment';
    private $refundPath = '/transactions/%s/refund';
    private $refundCustomIdPath = '/online-payment/%s/refund';
    private $onlinePaymentQueryPath = '/online-payment/query';

    const LIBRARY_NAME = 'Yedpay-php-library';
    const LIBRARY_VERSION = '1.4.2';

    const INDEX_GATEWAY_ALIPAY = 1;
    const INDEX_GATEWAY_ALIPAY_ONLINE = 4;

    const INDEX_GATEWAY_CODE_ALIPAY_ONLINE_WAP = '4_1';
    const INDEX_GATEWAY_CODE_ALIPAY_ONLINE_PC2MOBILE = '4_2';
    const INDEX_GATEWAY_CODE_WECHATPAY_ONLINE = '8_2';
    const INDEX_GATEWAY_CODE_UNIONPAY_EXPRESSPAY = '9_1';
    const INDEX_GATEWAY_CODE_UNIONPAY_UPOP = '9_5';
    const INDEX_GATEWAY_CODE_CREDIT_CARD_ONLINE = '12_1';

    const INDEX_WALLET_HK = 1;
    const INDEX_WALLET_CN = 2;
    const HK_WALLET = 'HK';
    const CN_WALLET = 'CN';

    const INDEX_CURRENCY_HKD = 1;
    const CURRENCY_HKD = 'HKD';

    /**
     * Client constructor.
     * @param $environment
     * @param $token
     * @param $isAccessToken
     * @throws \Exception
     */
    public function __construct($environment = null, $token = null, $isAccessToken = true)
    {
        if ($environment && $token) {
            $curl = new Curl();
            $library = new Library();
            $library->setEnvironment($environment);
            $isAccessToken ? $library->setAccessToken($token) : $library->setApiKey($token);
            $request = new Request();
            $curl->setLibrary($library)->setRequest($request);
            $this->setCurl($curl);
        }
        $this->wallet = static::HK_WALLET;
        $this->currency = static::CURRENCY_HKD;
        $this->gateway = static::INDEX_GATEWAY_ALIPAY;
    }

    /**
     * @param Curl $curl
     * @return Client
     */
    public function setCurl(Curl $curl)
    {
        $this->curl = $curl;
        return $this;
    }

    /**
     * @return Curl
     */
    public function getCurl()
    {
        return $this->curl;
    }

    /**
     * @param $storeId
     * @param $amount
     * @param $extraParam
     * @return Response\Response
     * @throws Exception
     */
    public function precreate($storeId, $amount, $extraParam = null)
    {
        if (!$this->curl) {
            throw new Exception('Please set curl with credentials first');
        }
        $this->path = sprintf($this->precreatePath, $storeId);
        $parameter = [
            'gateway_id' => (string) $this->getGateway(),
            'currency' => $this->getCurrency(),
            'wallet' => $this->getWallet(),
            'amount' => $amount,
        ];
        $parameter = $extraParam ? array_merge($parameter, ['extra_parameters' => $extraParam]) : $parameter;
        $parameter = $this->getNotifyUrl() ? array_merge($parameter, ['notify_url' => $this->getNotifyUrl()]) : $parameter;
        $parameter = $this->getReturnUrl() ? array_merge($parameter, ['return_url' => $this->getReturnUrl()]) : $parameter;
        return $this->curl->call($this->path, 'POST', $parameter);
    }

    /**
     * @param $customId
     * @param $amount
     * @return Response\Response
     * @throws Exception
     */
    public function onlinePayment($customId, $amount)
    {
        if (!$this->curl) {
            throw new Exception('Please set curl with credentials first');
        }
        $this->path = sprintf($this->onlinePaymentPath);
        $parameter = [
            'currency' => $this->getCurrency(),
            'notify_url' => $this->getNotifyUrl(),
            'return_url' => $this->getReturnUrl(),
            'custom_id' => $customId,
            'amount' => $amount,
        ];

        $parameter = $this->getSubject() ? array_merge($parameter, ['subject' => $this->getSubject()]) : $parameter;
        $parameter = $this->getExpiryTime() ? array_merge($parameter, ['expiry_time' => $this->getExpiryTime()]) : $parameter;
        if ($this->getGatewayCode()) {
            $parameter = array_merge($parameter, ['gateway_code' => $this->getGatewayCode()]);
            if (
                $this->getGatewayCode() == static::INDEX_GATEWAY_CODE_ALIPAY_ONLINE_WAP ||
                $this->getGatewayCode() == static::INDEX_GATEWAY_CODE_ALIPAY_ONLINE_PC2MOBILE
            ) {
                $parameter = array_merge($parameter, ['wallet' => $this->getWallet()]);
            }
        }

        // metadata
        preg_match("#^\d+(\.\d+)*#", phpversion(), $phpVersionArray);

        $metadataArray = [
            'php_library' => self::LIBRARY_VERSION,
            'php' => $phpVersionArray[0],
        ];
        if ($this->getMetadata()) {
            $requestMetadata = json_decode($this->getMetadata(), true);
            $metadataArray = array_merge($requestMetadata, $metadataArray);
        }
        $parameter = array_merge($parameter, ['metadata' => json_encode($metadataArray)]);

        $parameter = $this->getPaymentData() ? array_merge($parameter, ['payment_data' => $this->getPaymentData()]) : $parameter;

        $parameter = $this->getCheckoutDomainId() ? array_merge($parameter, ['checkout_domain_id' => $this->getCheckoutDomainId()]) : $parameter;

        return $this->curl->call($this->path, 'POST', $parameter);
    }

    /**
     * @param $custom_id
     * @return Response\Response
     * @throws Exception
     */
    public function queryOnlinePayment($custom_id)
    {
        if (!$this->curl) {
            throw new Exception('Please set curl with credentials first');
        }
        $this->path = $this->onlinePaymentQueryPath;

        $params = [];
        if (!empty($custom_id)) {
            $params['custom_id'] = $custom_id;
        }

        return $this->curl->call($this->path, 'GET', $params);
    }

    /**
     * @param $transactionId
     * @param $reason
     * @param $amount
     * @return Response\Response
     * @throws Exception
     */
    public function refund($transactionId, $reason = null, $amount = null)
    {
        if (!$this->curl) {
            throw new Exception('Please set curl with credentials first');
        }
        $this->path = sprintf($this->refundPath, $transactionId);

        $params = $this->setRefundParameters($reason, $amount);
        return $this->curl->call($this->path, 'POST', $params);
    }

    /**
     * @param $customId
     * @param $reason
     * @param $amount
     * @return Response\Response
     * @throws Exception
     */
    public function refundByCustomId($customId, $reason = null, $amount = null)
    {
        if (!$this->curl) {
            throw new Exception('Please set curl with credentials first');
        }
        $this->path = sprintf($this->refundCustomIdPath, $customId);

        $params = $this->setRefundParameters($reason, $amount, $customId);
        return $this->curl->call($this->path, 'PUT', $params);
    }

    /**
     * @param $data
     * @param $signKey
     * @param $unsetField
     * @return Response\Response
     * @throws Exception
     */
    public function verifySign($data, $signKey, $unsetField = [])
    {
        if (!is_array($data) || !is_string($signKey) || empty($data['sign']) || empty($data['sign_type'])) {
            return false;
        }

        $data = filter_var_array($data, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $sign = filter_var($data['sign'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $algorithm = filter_var($data['sign_type'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        unset($data['sign'], $data['sign_type']);
        if (!empty($unsetField)) {
            foreach ($unsetField as $field) {
                unset($data[$field]);
            }
        }
        ksort($data);
        $queryString = urldecode(http_build_query($data));

        switch (strtoupper($algorithm)) {
            case 'HMAC_SHA256':
                if (strlen($signKey) != 32) {
                    return false;
                }
                $verifySign = hash_hmac('sha256', $queryString, $signKey);
                break;
            default:
                return false;
        }

        return $verifySign == $sign;
    }

    /**
     * @return mixed
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param int $currency
     * @return Client
     * @throws Exception
     */
    public function setCurrency($currency)
    {
        switch ($currency) {
            case static::INDEX_CURRENCY_HKD:
                $this->currency = static::CURRENCY_HKD;
                break;
            default:
                throw new Exception('Currency not supported yet');
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getWallet()
    {
        return $this->wallet;
    }

    /**
     * @param int $wallet
     * @return Client
     * @throws Exception
     */
    public function setWallet($wallet)
    {
        switch ($wallet) {
            case static::INDEX_WALLET_HK:
                $this->wallet = static::HK_WALLET;
                break;
            case static::INDEX_WALLET_CN:
                $this->wallet = static::CN_WALLET;
                break;
            default:
                throw new Exception('This is not a valid wallet');
        }
        return $this;
    }

    /**
     * @param $gateway
     * @return $this
     * @throws Exception
     */
    public function setGateway($gateway)
    {
        switch ($gateway) {
            case static::INDEX_GATEWAY_ALIPAY:
            case static::INDEX_GATEWAY_ALIPAY_ONLINE:
                $this->gateway = $gateway;
                break;
            default:
                throw new Exception('Gateway not supported yet');
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getGateway()
    {
        return $this->gateway;
    }

    /**
     * Get the value of returnUrl
     */
    public function getReturnUrl()
    {
        return $this->returnUrl;
    }

    /**
     * Set the value of returnUrl
     *
     * @return  self
     */
    public function setReturnUrl($returnUrl)
    {
        $this->returnUrl = $returnUrl;

        return $this;
    }

    /**
     * Get the value of notifyUrl
     */
    public function getNotifyUrl()
    {
        return $this->notifyUrl;
    }

    /**
     * Set the value of notifyUrl
     *
     * @return  self
     */
    public function setNotifyUrl($notifyUrl)
    {
        $this->notifyUrl = $notifyUrl;

        return $this;
    }

    /**
     * Get the value of subject
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set the value of subject
     *
     * @return  self
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get the value of expiryTime
     */
    public function getExpiryTime()
    {
        return $this->expiryTime;
    }

    /**
     * Set the value of expiryTime
     *
     * @return  self
     */
    public function setExpiryTime($expiryTime)
    {
        if ($expiryTime >= 900 && $expiryTime <= 10800) {
            $this->expiryTime = $expiryTime;
        } else {
            throw new Exception('Expiry time out of range');
        }
        return $this;
    }

    /**
     * Get the value of gatewayCode
     */
    public function getGatewayCode()
    {
        return $this->gatewayCode;
    }

    /**
     * Set the value of gatewayCode
     *
     * @return  self
     */
    public function setGatewayCode($gatewayCode)
    {
        switch ($gatewayCode) {
            case static::INDEX_GATEWAY_CODE_ALIPAY_ONLINE_WAP:
            case static::INDEX_GATEWAY_CODE_ALIPAY_ONLINE_PC2MOBILE:
            case static::INDEX_GATEWAY_CODE_WECHATPAY_ONLINE:
            case static::INDEX_GATEWAY_CODE_UNIONPAY_EXPRESSPAY:
            case static::INDEX_GATEWAY_CODE_UNIONPAY_UPOP:
            case static::INDEX_GATEWAY_CODE_CREDIT_CARD_ONLINE:
                $this->gatewayCode = $gatewayCode;
                break;
            default:
                throw new Exception('Gateway not supported yet');
        }
        return $this;
    }

    /**
     * Get the value of metadata
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * Set the value of metadata
     *
     * @return  self
     */
    public function setMetadata($metadata)
    {
        $metadataArray = json_decode($metadata, true);
        if (!is_array($metadataArray)) {
            throw new Exception('metadata should be JSON type');
        }

        $validateKeyArray = [
            'opencart',
            'yedpay_for_opencart',
            'woocommerce',
            'yedpay_for_woocommerce',
            'wordpress',
            'yedpay_for_magento',
            'magento',
            'yedpay_for_shopify',
            'shopify',
        ];

        foreach ($metadataArray as $key => $array) {
            if (!in_array($key, $validateKeyArray)) {
                throw new Exception('metadata should not contain invalid field');
            }
        }

        $this->metadata = $metadata;

        return $this;
    }

    /**
     * Get the value of paymentData
     */
    public function getPaymentData()
    {
        return $this->paymentData;
    }

    /**
     * Set the value of paymentData
     *
     * @return  self
     */
    public function setPaymentData($paymentData)
    {
        // check json and parameters
        $paymentDataArray = json_decode($paymentData, true);
        if (!is_array($paymentDataArray)) {
            throw new Exception('payment data should be JSON type');
        }

        $validateKeyArray = [
            'first_name',
            'last_name',
            'email',
            'phone',
            'billing_country',
            'billing_city',
            'billing_address1',
            'billing_address2',
            'billing_post_code',
            'billing_state',
        ];

        foreach ($paymentDataArray as $key => $array) {
            if (!in_array($key, $validateKeyArray)) {
                throw new Exception('payment data should not contain invalid field');
            }
        }

        $this->paymentData = $paymentData;

        return $this;
    }

    /**
     * Set the parameters of refund
     *
     * @param $reason
     * @param $amount
     * @param $customId
     * @return array
     * @throws Exception
     */
    public function setRefundParameters($reason = null, $amount = null, $customId = null)
    {
        $params = [];
        if (!empty($customId)) {
            $params['custom_id'] = $customId;
        }
        if (!empty($reason)) {
            $params['refund_reason'] = $reason;
        }
        if (!empty($amount)) {
            if (!is_numeric($amount)) {
                throw new Exception('Refund amount should be numeric');
            }
            $params['amount'] = $amount;
        }
        return $params;
    }

    /**
     * Get the value of checkoutDomainId
     */
    public function getCheckoutDomainId()
    {
        return $this->checkoutDomainId;
    }

    /**
     * Set the value of checkoutDomainId
     *
     * @return  self
     */
    public function setCheckoutDomainId($checkoutDomainId)
    {
        $this->checkoutDomainId = $checkoutDomainId;

        return $this;
    }
}
