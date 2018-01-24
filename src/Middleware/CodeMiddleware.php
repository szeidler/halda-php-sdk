<?php

namespace Halda\Middleware;

use Psr\Http\Message\RequestInterface;

class CodeMiddleware
{

    protected $config;

    /**
     * CodeMiddleware constructor.
     *
     * @param $config
     *   Holds the configuration to initialize the service client.
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * Invokes the code for the application.
     *
     * @param callable $handler
     *
     * @return \Closure
     */
    public function __invoke(callable $handler)
    {
        return function (RequestInterface $request, array $options) use (&$handler) {
            $request = $this->applyToken($request);
            return $handler($request, $options);
        };
    }

    /**
     * Returns the code from the configuration.
     *
     * @return string|null
     */
    public function getCode()
    {
        if (!empty($this->config['code'])) {
            return $this->config['code'];
        } else {
            return null;
        }
    }

    /**
     * Validates the code used for the API authentication.
     *
     * @param string $code
     *   Code for authenticating the API request.
     *
     * @return bool
     *   True if the provided code is valid.
     */
    public function validateCode($code)
    {
        if (empty($code)) {
            throw new \InvalidArgumentException('A authentication code must be provided.');
        }
        if (!is_string($code)) {
            throw new \InvalidArgumentException('The provided code is not a string.');
        }
        if (strlen($code) < 4) {
            throw new \InvalidArgumentException('The provided code must be longer than 3 characters.');
        }
        return true;
    }

    /**
     * Applies the code to the request.
     *
     * @param \Psr\Http\Message\RequestInterface $request
     *
     * @return static
     */
    protected function applyToken(RequestInterface $request)
    {
        $token = $this->getCode();
        $this->validateCode($token);
        return $request->withHeader('Code', $this->getCode());
    }
}
