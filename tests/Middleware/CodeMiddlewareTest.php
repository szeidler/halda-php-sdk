<?php

namespace Halda\Tests\Middleware;

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
}
