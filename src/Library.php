<?php

namespace Yedpay;

use Exception;

class Library
{
    protected $production = 'https://api.yedpay.com';
    protected $staging = 'https://api-staging.yedpay.com';
    protected $version = 'v1';
    protected $environment = 'staging';
    protected $accessToken = null;
    protected $apiKey = null;

    const PRODUCTION = 'production';
    const STAGING = 'staging';

    /**
     * Get the value of production
     */
    public function getProduction()
    {
        return $this->production;
    }

    /**
     * Set the value of production
     *
     * @param $production
     * @return  self
     */
    public function setProduction($production)
    {
        $this->production = $production;

        return $this;
    }

    /**
     * Get the value of staging
     */
    public function getStaging()
    {
        return $this->staging;
    }

    /**
     * Set the value of staging
     *
     * @param $staging
     * @return  self
     */
    public function setStaging($staging)
    {
        $this->staging = $staging;

        return $this;
    }

    /**
     * Get the value of version
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Set the value of version
     *
     * @param $version
     * @return  self
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Get the value of accessToken
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * Set the value of accessToken
     *
     * @param $accessToken
     * @return  self
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    /**
     * Get the value of environment
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * Set the value of environment
     *
     * @param $environment
     * @return  self
     * @throws Exception
     */
    public function setEnvironment($environment)
    {
        if ($environment != static::PRODUCTION && $environment != static::STAGING) {
            throw new Exception('invalid Environment');
        }
        $this->environment = $environment;

        return $this;
    }

    /**
     * Get the value of endpoint
     */
    public function getEndpoint()
    {
        $environment = $this->{'get' . $this->getEnvironment()}();
        return implode('/', [$environment, $this->getVersion()]);
    }

    /**
     * Get the value of apiKey
     */ 
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * Set the value of apiKey
     *
     * @return  self
     */ 
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }
}
