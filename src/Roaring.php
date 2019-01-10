<?php

namespace Olssonm\Roaring;

/**
 * Main class for the Roaring API
 */
class Roaring
{
    /**
     * The fetched token
     * @var stdClass
     */
    private $token;

    /**
     * API consumer key
     * @var string
     */
    private $key;

    /**
     * API consumer secret
     * @var string
     */
    private $secret;

    /**
     * Base URL for the API
     * @var string
     */
    private const BASE_URL = 'https://api.roaring.io';

    /**
     * Constructor
     * @param string $key
     * @param string $secret
     */
    public function __construct(string $key = '', string $secret = '')
    {
        $this->key = $key;
        $this->secret = $secret;

        $this->token = $this->getToken();

        return $this;
    }

    /**
     * Make a GET-request
     * @param  string $path
     * @param  array  $headers
     * @param  array  $parameters
     * @return stdClass
     */
    public function get(string $path, array $headers = [], array $parameters = [])
    {
        if ($this->token) {
            $headers += [
                'Authorization' => sprintf('Bearer %s', $this->token->access_token)
            ];
        }

        $url = sprintf('%s%s', (string) self::BASE_URL, $path);

        $response = Request::get($url)
            ->addHeaders($headers)
            ->sendsType(\Httpful\Mime::FORM)
            ->expectsJson()
            ->send();

        return $response->body;
    }

    /**
     * Make a POST-request
     * @param  string $path
     * @param  array  $headers
     * @param  array  $body
     * @return stdClass
     */
    public function post(string $path, array $headers = [], array $body = [])
    {
        if ($this->token) {
            $headers += [
                'Authorization' => sprintf('Bearer %s', $this->token->access_token)
            ];
        }

        $url = sprintf('%s%s', (string) self::BASE_URL, $path);

        $response = Request::post($url)
            ->addHeaders($headers)
            ->sendsType(\Httpful\Mime::FORM)
            ->body($body)
            ->send();

        return $response->body;
    }

    /**
     * Predifined setup to retrieve a token
     * @return stdClass
     */
    private function getToken()
    {
        $headers = [
            'Cache-Control' => 'no-cache',
            'Content-Type' => 'application/x-www-form-urlencoded',
            'authorization' => sprintf('Basic %s', (string) base64_encode($this->key . ':' . $this->secret))
        ];

        $parameters = [
            'grant_type' => 'client_credentials'
        ];

        $response = $this->post('/token', $headers, $parameters);

        return $response;
    }
}
