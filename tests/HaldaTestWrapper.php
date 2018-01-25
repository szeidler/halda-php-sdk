<?php

namespace Halda\Tests;

use PHPUnit\Framework\TestCase;
use Halda\HaldaClient;

class HaldaTestWrapper extends TestCase
{

    protected $client;

    public function setUp()
    {
        parent::setUp();

        $this->client = new HaldaClient(
            [
            'baseUrl'  => getenv('BASE_URL'),
            'code' => getenv('API_CODE'),
            ]
        );
    }
}
