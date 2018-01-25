<?php

namespace Halda\Helper;

/**
 * Class DateHelper
 *
 * @package Halda\Helper
 */
class DateHelper
{

    /**
     * Converts a timestamp to a java timestamp object,
     *
     * @param int $timestamp
     *   Timestamp in the current site timezone.
     *
     * @return string
     *   Java timestamp object.
     */
    public static function convertTimestampToDate($timestamp)
    {
        // The java date object expects a timestamp with milliseconds.
        $timestamp_milli = $timestamp * 1000;
        return '/Date(' . $timestamp_milli . ')/';
    }
}
