<?php

namespace Yedpay;

class Library
{
    protected $production = 'https://api.yedpay.com';
    protected $staging = 'https://api-staging.yedpay.com';
    protected $version = 'v1';
    protected $environment = null;
    protected $accessToken = null;
    protected $endpoint = null;

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
     * @return  self
     */
    public function setVersion($version)
    {
        $this->version = $version;

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
     * @return  self
     */
    public function setEnvironment($environment)
    {
        $this->environment = $environment;

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
     * @return  self
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    /**
     * Get the value of endpoint
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * Set the value of endpoint
     *
     * @return  self
     */
    public function setEndpoint($endpoint)
    {
        $this->endpoint = $endpoint;

        return $this;
    }
}
