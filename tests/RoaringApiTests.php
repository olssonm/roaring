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

        // Load the configuration
        $this->key = $config['key'];
        $this->secret = $config['secret'];

        parent::setUp();
    }

    /** @test **/
    public function test_token()
    {
        $token = $this->get_token();

        // Test token attributes
        $this->assertObjectHasAttribute('access_token', $token);
        $this->assertObjectHasAttribute('scope', $token);
        $this->assertObjectHasAttribute('token_type', $token);
        $this->assertObjectHasAttribute('expires_in', $token);
        $this->assertEquals('Bearer', $token->token_type);

        // Retest the same reused token
        $reusedToken = (new Roaring($this->key, $this->secret))->getToken();

        $this->assertEquals($reusedToken->access_token, $token->access_token);
        $this->assertEquals($reusedToken->token_type, $token->token_type);
        $this->assertEquals($reusedToken->scope, $token->scope);
    }

    /** @test **/
    public function test_basic_company_search()
    {
        $response = (new Roaring($this->key, $this->secret))
            ->get('/se/company/overview/1.1/5567164818')
            ->getResponse();

        $this->assertEquals('200', $response->code);
        $this->assertEquals('5567164818', $response->body->companyId);
    }

    /** @test **/
    public function test_basic_signing_combination()
    {
        $response = (new Roaring($this->key, $this->secret))
            ->get('/se/company/signing-combinations/1.0/combinations/556716-4818')
            ->getResponse();

        $this->assertEquals('200', $response->code);
        $this->assertEquals('Efternamn2401, Petra', $response->body->combinations[0][0]->name);
    }

    /** @test **/
    public function test_failed_signing_combination()
    {
        $response = (new Roaring($this->key, $this->secret))
            ->get('/se/company/signing-combinations/1.0/combinations/556716-4812')
            ->getResponse();

        $this->assertEquals('404', $response->code);
    }

    /** @test **/
    public function test_bad_signing_combination()
    {
        $response = (new Roaring($this->key, $this->secret))
            ->get('/se/company/signing-combinations/1.0/combinations/556903-0264')
            ->getResponse();

        $this->assertEquals('404', $response->code);
    }

    /** @test **/
    public function test_bad_endpoint()
    {
        $response = (new Roaring($this->key, $this->secret))
            ->get('/se/company/bad-endpoint/1.0/5565002465')
            ->getResponse();

        $this->assertEquals('404', $response->code);
    }

    /** @notatest **/
    private function get_token()
    {
        return (new Roaring($this->key, $this->secret))->getToken();
    }
}
