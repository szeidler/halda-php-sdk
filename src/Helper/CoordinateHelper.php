<?php

namespace Halda\Helper;

use GuzzleHttp\Command\Guzzle\Parameter;

class CoordinateHelper
{

    /**
     * Converts the decimal coordinate to the expected integer format.
     *
     * @param float                                $value
     *   Decimal coordinate.
     * @param \GuzzleHttp\Command\Guzzle\Parameter $parameter
     *   Currently processed paramter.
     *
     * @return int
     *   Coordinate as an integer.
     */
    public static function convertDecimalCoordinateToExpectedFormat($value, Parameter $parameter)
    {
        return (int)($value * 100000);
    }
}