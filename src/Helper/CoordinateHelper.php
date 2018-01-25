<?php

namespace Halda\Helper;

/**
 * Class CoordinateHelper
 *
 * @package Halda\Helper
 */
class CoordinateHelper
{

    /**
     * Converts the decimal coordinate to the expected integer format.
     *
     * @param float $value
     *   Decimal coordinate.
     *
     * @return int
     *   Coordinate as an integer.
     */
    public static function convertDecimalCoordinateToExpectedFormat($value)
    {
        return (int)($value * 100000);
    }
}
