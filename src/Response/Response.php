<?php


namespace Yedpay\Response;

/**
 * Class Response
 * @package Yedpay\Response
 */
class Response
{
    private $message;

    /**
     * Response constructor.
     * @param $message
     */
    public function __construct($message = null)
    {
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return bool
     */
    public function hasMessage()
    {
        return !empty($this->message);
    }


}