<?php

namespace Olssonm\Roaring;

/**
 *
 */
class Roaring
{
    private $token;

    private $key;

    private $secret;

    public function __construct($key, $secret)
    {
        $this->key = $key;
        $this->secret = $secret;

        return $this;
    }

    public function token()
    {
        $headers = [
            'Cache-Control' => 'no-cache',
            'Content-Type' => 'application/x-www-form-urlencoded',
            'authorization' => sprintf('Basic ', (string) base64_encode($this->key . ':' . $this->secret))
        ];

        $parameters = [
            'grant_type' => 'client_credentials'
        ];

        return $this->token = $this->post('/token', $headers, $parameters);
    }

    public function get(string $path, array $headers = [], array $parameters = [])
    {
        $response = (new Request())->get($path, $headers, $parameters);
        return $response;
    }

    public function post(string $path, array $headers = [], array $parameters = [])
    {
        $response = (new Request())->post($path, $headers, $body);
        return $response;
    }
}
