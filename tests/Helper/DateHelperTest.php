<?php

namespace Halda\Tests\Helper;

use GuzzleHttp\Command\Guzzle\Parameter;
use Halda\Helper\DateHelper;
use PHPUnit\Framework\TestCase;

/**
 * Tests the DateHelper class.
 *
 * @package Halda\Tests\Helper
 * @see     \Halda\Helper\CoordinateHelper
 */
class DateHelperTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();
    }

    /**
     * Tests the timestamp to date object conversion.
     */
    public function testConvertTimestampToDate()
    {
        $dateHelper = new DateHelper();
        $timestamp = 1516880139;
        $expected = "/Date(1516883739000)/";
        $this->assertEquals(
            $expected,
            $dateHelper::convertTimestampToDate($timestamp),
            'The converted date should match the expected value.'
        );
    }

    /**
     * Tests the timestamp to date object conversion in an parameter.
     */
    public function testConvertTimestampToDateWithParameterFilter()
    {
        $data = [
          'name'    => 'ScheduledTime',
          'type'    => 'string',
          'filters' => ['Halda\\Helper\\DateHelper::convertTimestampToDate'],
        ];
        $parameter = new Parameter($data);
        $this->assertEquals('/Date(1516883739000)/', $parameter->filter(1516880139));
    }
}
