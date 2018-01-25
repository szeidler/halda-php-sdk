<?php

namespace Halda\Tests\Operation;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7;
use Halda\HaldaClient;
use Halda\Tests\HaldaTestWrapper;

class PlaceOrderTest extends HaldaTestWrapper
{

    protected $data;

    public function setUp()
    {
        parent::setUp();

        $this->data = [
          'NoOfSeats'  => 2,
          'Transports' => [
            'TransportAddresses' => [
              [
                'Name'              => 'John Doe',
                'NodeSeqNo'         => 1,
                'ActionType'        => 2,
                'LocationName'      => 'Tromsø',
                'StreetName'        => 'Kirkegata',
                'StreetNo'          => '12',
                'Latitude'          => 59.63986,
                'Longitude'         => 19.63986,
                'AddressTime'       => 1516878017,
                'RequestedLateTime' => 1516878017,
                'ScheduledTime'     => 1516878017,
              ],
              [
                'Name'              => 'John Doe',
                'NodeSeqNo'         => 2,
                'ActionType'        => 3,
                'LocationName'      => 'Tromsø',
                'StreetName'        => 'Flyplassvegen',
                'StreetNo'          => '32',
                'Latitude'          => 59.33986,
                'Longitude'         => 19.33986,
                'AddressTime'       => 1516878117,
                'RequestedLateTime' => 1516878117,
                'ScheduledTime'     => 1516878117,
              ],
            ],
          ],
        ];
    }

    /**
     * Test placing a valid order.
     */
    public function testPlaceOrder()
    {
        $data = $this->data;

        // Setup a mock.
        $stream = Psr7\stream_for('{"Message" : "Successfully ordered.", "OrderId" : 12345, "Status" : 1}');
        $mock = new MockHandler([
          new Response(200, ['Content-Type' => 'application/json'], $stream),
        ]);

        $handler = HandlerStack::create($mock);
        $guzzleClient = new Client(['handler' => $handler]);

        $client = new HaldaClient([
          'client'  => $guzzleClient,
          'baseUrl' => getenv('BASE_URL'),
          'code'    => getenv('API_CODE'),
        ]);

        // Perform the command.
        $response = $client->placeOrder($data);

        // Assertions on the response.
        $this->assertEquals(12345, $response->offsetGet('OrderId'), 'The OrderId must match the mocked response.');
        $this->assertEquals(1, $response->offsetGet('Status'), 'The Status must match the mocked response.');
    }

    /**
     * Test placing an invalid order throws an exception.
     *
     * @expectedException GuzzleHttp\Command\Exception\CommandException
     */
    public function testPlaceInvalidOrder()
    {
        $data = $this->data;

        // Make order object invalid.
        unset($data['NoOfSeats']);

        // Perform the command.
        $response = $this->client->placeOrder($data);
    }
}
