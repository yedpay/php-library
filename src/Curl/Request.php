<?php

namespace Yedpay\Curl;

use Yedpay\Client;

class Request implements HttpRequest
{
    private $handle = null;

    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->handle = curl_init();
    }

    /**
     * setOptionArray
     *
     * @param mixed $url
     * @param mixed $method
     * @param mixed $parameters
     * @param mixed $token
     * @param boolean $isAccessToken
     * @return void
     */
    public function setOptionArray($url, $method, $parameters, $token, $isAccessToken = true)
    {
        $method = strtoupper($method);
        if ($method == 'GET' && !empty($parameters)) {
            $url .= '?' . http_build_query($parameters);
        }

        curl_setopt_array($this->handle, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => (in_array($method, ['POST', 'PUT']) && !empty($parameters)) ? http_build_query($parameters) : null,
            CURLOPT_REFERER => isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null,
            CURLOPT_HTTPHEADER => [
                'Authorization: ' . ($isAccessToken ? 'Bearer ' : 'API-KEY ') . $token,
                'Content-Type: application/x-www-form-urlencoded',
                'User-Agent: ' . Client::LIBRARY_NAME . '/' . Client::LIBRARY_VERSION,
            ]
        ]);
    }

    /**
     * execute
     *
     * @return void
     */
    public function execute()
    {
        return curl_exec($this->handle);
    }

    /**
     * error
     *
     * @return void
     */
    public function error()
    {
        return curl_error($this->handle);
    }

    /**
     * close
     *
     * @return void
     */
    public function close()
    {
        curl_close($this->handle);
    }
}
