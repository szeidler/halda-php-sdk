<?php

namespace Halda\Tests\Helper;

use GuzzleHttp\Command\Guzzle\Parameter;
use Halda\Helper\CoordinateHelper;
use PHPUnit\Framework\TestCase;

/**
 * Tests the CoordinateHelper class.
 *
 * @package Halda\Tests\Helper
 * @see     \Halda\Helper\CoordinateHelper
 */
class CodeMiddlewareTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();
    }

    /**
     * Tests the decimal coordinate to the expected integer format conversion.
     */
    public function testConvertDecimalNumberConversion()
    {
        $coordinateHelper = new CoordinateHelper();
        $coordinate = 62.1241240;
        $expected = 6212412;
        $this->assertEquals(
            $expected,
            $coordinateHelper::convertDecimalCoordinateToExpectedFormat($coordinate),
            'The converted coordinate should match the expected value.'
        );
    }

    /**
     * Tests the decimal coordinate to the expected integer format conversion in an parameter.
     */
    public function testConvertDecimalNumberConversionWithParameterFilter()
    {
        $data = [
          'name'    => 'latitude',
          'type'    => 'numeric',
          'filters' => ['Halda\\Helper\\CoordinateHelper::convertDecimalCoordinateToExpectedFormat'],
        ];
        $parameter = new Parameter($data);
        $this->assertEquals(6212412, $parameter->filter(62.1241240));
    }
}
