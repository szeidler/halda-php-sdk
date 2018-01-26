<?php

namespace Halda\Tests\Middleware;

use Halda\HaldaClient;
use Halda\Middleware\CodeMiddleware;
use Halda\Tests\HaldaTestWrapper;

/**
 * Tests the CodeMiddleware class.
 *
 * @package Halda\Tests\Middleware
 * @see     \Halda\Middleware\CodeMiddleware
 */
class CodeMiddlewareTest extends HaldaTestWrapper
{

    public function setUp()
    {
        parent::setUp();
    }

    /**
     * Test, that the code getter returns the initialized code or null, if not provided.
     */
    public function testCodeGetter()
    {
        $code = 'mytoken';
        $middleware = new CodeMiddleware(['code' => $code]);
        $this->assertEquals($code, $middleware->getCode(), 'The code getter must return the right code.');

        $middleware = new CodeMiddleware();
        $this->assertNull(
          $middleware->getCode(),
          'The code getter must return null, when it was initialized without a code.'
        );
    }

    /**
     * Dataprovider providing invalid tokens.
     *
     * @return array
     */
    public function invalidCodes()
    {
        return [
          'empty'        => [''],
          'a'            => ['a'],
          'ab'           => ['ab'],
          'abc'          => ['abc'],
          'digit'        => [1],
          'double-digit' => [12],
          'triple-digit' => [123],
          'bool'         => [true],
          'array'        => [['token']],
        ];
    }

    /**
     * Dataprovider providing valid tokens.
     *
     * @return array
     */
    public function validCodes()
    {
        return [
          'token'      => ['token'],
          'short-hash' => ['123456789'],
          'full-hash'  => ['akrwejhtn983z420qrzc8397r4'],
        ];
    }


    /**
     * Tests, that the client throws an exception on invalid codes.
     *
     * @dataProvider invalidCodes
     * @expectedException InvalidArgumentException
     */
    public function testTokenMiddlewareRaisesExceptionOnInvalidToken($token)
    {
        $middleware = new CodeMiddleware();
        $middleware->validateCode($token);
    }

    /**
     * Tests, that valid tokens are considered to be valid.
     *
     * @dataProvider validCodes
     */
    public function testTokenMiddlewareValidatesSuccessfullyOnValidToken($token)
    {
        $middleware = new CodeMiddleware();
        $this->assertTrue($middleware->validateCode($token), 'The token must be valid.');
    }

    /**
     * Tests, that the Halda Client request uses the injected authentication code.
     */
    public function testThatHaldaClientRequestIncludesCode()
    {
        $client = new HaldaClient(
          [
            'baseUrl' => getenv('BASE_URL'),
            'code'    => getenv('API_CODE'),
          ]
        );

        // Build json data to be sent.
        $data = json_encode(['x' => 1, 'y' => 2, 'z' => 3]);
        $response = $client->getHttpClient()
          ->post('http://httpbin.org/post', ['body' => $data, 'headers' => ['Content-Type' => 'application/json']]);

        // Decode the response.
        $body = (string)$response->getBody();
        $json = json_decode($body, true);

        // Check that the code was transferred with the request and matches the configuration.
        $this->assertArrayHasKey('Code', $json['json'], 'The response must include the code.');
        $this->assertEquals(getenv('API_CODE'), $json['json']['Code'], 'The send code must match the configured code.');
    }
}
