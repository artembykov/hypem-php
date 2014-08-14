<?php
namespace Hypem;

class Request
{
    const BASE_URI = 'http://hypem.com';

    private $curl;
    private $has_cookie = false;

    public function __construct()
    {
        $this->curl = new \Curl\Curl();

        $this->curl->success(function ($instance) {
            if (!$this->has_cookie) {
                $instance->setOpt(CURLOPT_COOKIE, $instance->response_headers['Set-Cookie']);
                $this->has_cookie = true;
            }
        });
    }

    public function getResponseHeader($name)
    {
        return $this->curl->response_headers[$name];
    }

    public function get($path)
    {
        $this->curl->get($path);
        return $this->curl->raw_response;
    }

    public function getJson($path)
    {
        $response = json_decode($this->get($path), true);
        unset($response['version']);
        return $response;
    }
}
