<?php

namespace Yedpay\Response;

/**
 * Class Success
 * @package Yedpay\Response
 */
class Success extends Response
{
    private $data;

    /**
     * Success constructor.
     * @param $response
     */
    public function __construct($response)
    {
        if (!empty($response['message'])) {
            parent::__construct($response['message']);
        }
        if (!empty($response['data'])) {
            $this->data = $response['data'];
        }
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return bool
     */
    public function hasData()
    {
        return $this->data !== null;
    }

}