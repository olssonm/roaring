<?php

namespace Olssonm\Roaring;

/**
 * Main class for the Roaring API-wrapper
 */
class Roaring
{
    /**
     * The fetched token
     *
     * @var \stdClass
     */
    private $token;

    /**
     * API consumer key
     *
     * @var string
     */
    private $key;

    /**
     * API consumer secret
     *
     * @var string
     */
    private $secret;

    /**
     * The last response
     *
     * @var mixed
     */
    private $response;

    /**
     * Base URL for the API
     *
     * @var string
     */
    private const BASE_URL = 'https://api.roaring.io';

    /**
     * Constructor
     *
     * @param string $key
     * @param string $secret
     */
    public function __construct(string $key = '', string $secret = '', \stdClass $token = null)
    {
        $this->key = $key;
        $this->secret = $secret;

        ($token) ? $this->token = $token : $this->token = $this->generateToken();
    }

    /**
     * Make a GET-request
     *
     * @param  string $path
     * @param  array  $headers
     * @param  array  $parameters
     * @return Roaring
     */
    public function get(string $path, array $headers = [], array $parameters = []): Roaring
    {
        if ($this->token) {
            $headers += [
                'Authorization' => sprintf('%s %s', $this->token->token_type, $this->token->access_token)
            ];
        }

        $url = sprintf('%s%s', (string) self::BASE_URL, $path);

        $this->response = Request::get($url)
            ->addHeaders($headers)
            ->sendsType(\Httpful\Mime::FORM)
            ->send();

        return $this;
    }

    /**
     * Make a POST-request
     *
     * @param  string $path
     * @param  array  $headers
     * @param  array  $body
     * @return Roaring
     */
    public function post(string $path, array $headers = [], array $body = []): Roaring
    {
        if ($this->token) {
            $headers += [
                'Authorization' => sprintf('%s %s', $this->token->token_type, $this->token->access_token)
            ];
        }

        $url = sprintf('%s%s', (string) self::BASE_URL, $path);

        $this->response = Request::post($url)
            ->addHeaders($headers)
            ->sendsType(\Httpful\Mime::FORM)
            ->body($body)
            ->send();

        return $this;
    }

    /**
     * Retrive the response from the last request
     *
     * @param  string $type
     * @return mixed
     */
    public function getResponse(string $type = null)
    {
        if ($this->response) {
            if ($type) {
                return $this->response->{$type};
            }
            return $this->response;
        }

        throw new \Exception("No response available", 1);
    }

    /**
     * Return the generated token
     *
     * @return \stdClass
     */
    public function getToken(): \stdClass
    {
        return $this->token;
    }

    /**
     * Predifined setup to retrieve a token
     *
     * @return \stdClass
     */
    private function generateToken(): \stdClass
    {
        $headers = [
            'Cache-Control' => 'no-cache',
            'authorization' => sprintf('Basic %s', (string) base64_encode($this->key . ':' . $this->secret))
        ];

        $parameters = [
            'grant_type' => 'client_credentials'
        ];

        return $this->post('/token', $headers, $parameters)->getResponse('body');
    }
}
