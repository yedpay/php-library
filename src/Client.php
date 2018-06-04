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
    private $curl = null;
    private $path;
    private $currency;
    private $wallet;
    private $gateway;

    private $precreatePath = '/precreate/%s';

    const INDEX_GATEWAY_ALIPAY = 1;


    const INDEX_WALLET_HK = 1;
    const INDEX_WALLET_CN = 2;
    const HK_WALLET = "HK";
    const CN_WALLET = "CN";

    const INDEX_CURRENCY_HKD = 1;
    const INDEX_CURRENCY_RMB = 2;
    const CURRENCY_HKD = "HKD";
    const CURRENCY_RMB = "RMB";


    /**
     * Client constructor.
     * @param $environment
     * @param $accessToken
     * @throws \Exception
     */
    public function __construct($environment = null, $accessToken = null)
    {
        if ($environment && $accessToken) {
            $curl = new Curl();
            $library = new Library();
            $library->setEnvironment($environment);
            $library->setAccessToken($accessToken);
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
            'gateway_id' => $this->gateway,
            'currency' => $this->currency,
            'wallet' => $this->wallet,
            'amount' => $amount,
        ];
        if ($extraParam) {
            $parameter = array_merge($parameter, ['extra_parameters' => $extraParam]);
        }
        return $this->curl->call($this->path, 'post', $parameter);
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
            case static::INDEX_CURRENCY_RMB:
                $this->currency = static::CURRENCY_RMB;
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

}