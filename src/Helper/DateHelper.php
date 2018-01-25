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
        $timezone_oslo = new \DateTimeZone(date_default_timezone_get());
        $timezone_utc = new \DateTimeZone('UTC');
        $now = new \DateTime('now', $timezone_utc);
        $offset = $timezone_oslo->getOffset($now);

        // Add the timezone offset, which includes conditional DST.
        $timestamp += $offset;
        $timestamp_milli = $timestamp * 1000;
        return '/Date(' . $timestamp_milli . ')/';
    }
}
