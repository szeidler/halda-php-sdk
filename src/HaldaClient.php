<?php

namespace Halda;

use Halda\Middleware\CodeMiddleware;
use GuzzleHttp\Client;
use GuzzleHttp\Command\Guzzle\Description;
use GuzzleHttp\Command\Guzzle\GuzzleClient;
use GuzzleHttp\HandlerStack;

/**
 * Main Client, that invokes the service description and handles all requests.
 *
 * @package Halda
 */
class HaldaClient extends GuzzleClient
{

    /**
     * HaldaClient constructor.
     *
     * @param array $config
     *   Holds the configuration to initialize the service client.
     */
    public function __construct(array $config = [])
    {
        parent::__construct(
            $this->getClientFromConfig($config),
            $this->getServiceDescriptionFromConfig($config),
            null,
            null,
            null,
            $config
        );
    }

    /**
     * Returns the service client.
     *
     * The service client will be returned based on a injected client object
     * or created with a default configuration.
     *
     * @param array $config
     *   Holds the configuration to initialize the service client.
     *
     * @return \GuzzleHttp\Client
     */
    private function getClientFromConfig(array $config)
    {
        // If a client was provided, return it.
        if (isset($config['client'])) {
            return $config['client'];
        }

        $stack = $this->initializeClientHandlerStack($config);

        // Ensure, that a baseUrl was provided.
        if (empty($config['baseUrl'])) {
            throw new \InvalidArgumentException('A baseUrl must be provided.');
        }

        // Initialize client config.
        $client_config = ['base_uri' => $config['baseUrl'], 'handler' => $stack];

        // Apply provided client configuration, if available.
        if (isset($config['client_config'])) {
            // Ensure, the client_config is an array.
            if (!is_array($config['client_config'])) {
                throw new \InvalidArgumentException('A client_config must be an array.');
            }
            $client_config += $config['client_config'];
        }

        // Create a Guzzle client.
        $client = new Client($client_config);

        return $client;
    }

    /**
     * Returns the service description.
     *
     * The service description will be returned based on a injected
     * configuration object or created based on the general service description
     * file.
     *
     * @param array $config
     *    Holds the configuration to initialize the service client.
     *
     * @return \GuzzleHttp\Command\Guzzle\Description
     */
    private function getServiceDescriptionFromConfig(array $config)
    {
        // If a description was provided, return it.
        if (isset($config['description'])) {
            return $config['description'];
        }

        // Ensure, that a baseUrl was provided.
        if (empty($config['baseUrl'])) {
            throw new \InvalidArgumentException('A baseUrl must be provided.');
        }

        // Create new description based of the stored JSON definition.
        $description = new Description(
            ['baseUrl' => $config['baseUrl']]
            + (array)json_decode(
                file_get_contents(__DIR__ . '/../service.json'),
                true
            )
        );

        return $description;
    }

    /**
     * Initializes the basic client handler stack.
     *
     * @param array $config
     *   Holds the configuration to initialize the service client.
     *
     * @return \GuzzleHttp\HandlerStack
     */
    private function initializeClientHandlerStack(array $config)
    {
        $stack = HandlerStack::create();

        // Adds the CodeMiddleware.
        $stack->push(new CodeMiddleware($config));

        return $stack;
    }
}
