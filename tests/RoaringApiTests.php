<?php

namespace Olssonm\Roaring;

use PHPUnit\Framework\TestCase;

/**
 *
 */
class RoaringApiTests extends TestCase
{
    private $key;

    private $secret;

    /**
     * Setup
     */
    public function setUp()
    {
        $config = json_decode(file_get_contents(__DIR__ . '/config.json'), true);

        if (!isset($config['key']) || !isset($config['key'])) {
            throw new \Exception("No valid test keys available for testing. Check 'config.json'.", 1);
            return;
        }

        $this->key = $config['key'];
        $this->secret = $config['secret'];

        parent::setUp();
    }

    /** @test **/
    public function test_token()
    {
        $response = (new Roaring($this->key, $this->secret))->getResponse('body');

        $this->assertObjectHasAttribute('access_token', $response);
        $this->assertObjectHasAttribute('scope', $response);
        $this->assertObjectHasAttribute('token_type', $response);
        $this->assertObjectHasAttribute('expires_in', $response);

        $this->assertEquals('Bearer', $response->token_type);
    }

    /** @test **/
    public function test_basic_signing_combination()
    {
        $response = (new Roaring($this->key, $this->secret))
            ->get('/se/company/signing-combinations/1.0/combinations/556716-4818')
            ->getResponse();

        $this->assertEquals('200', $response->code);
    }
}
