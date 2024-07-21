<?php

use Illuminate\Support\Carbon;

/**
 * Checks if file is a CSV extension
 *
 * @return bool
 */
if (! function_exists('checkCsvFile')) {
    function checkCsvFile(string $file): bool
    {
        $pathInfo = pathinfo($file);
        if (! empty($pathInfo['extension']) && $pathInfo['extension'] == 'csv') {
            return true;
        }

        return false;
    }
}
/**
 * Checks if the day falls on weekend
 *
 * @return bool
 */
if (! function_exists('isWeekEndDay')) {
    function isWeekEndDay(Carbon $day): bool
    {
        return $day->isWeekEnd();

    }
}
/**
 * Check week Day
 *
 * @return bool
 */
if (! function_exists('isWeekEndDay')) {
    function isWeekEndDay(Carbon $day): bool
    {
        return $day->isWeekEnd();

    }
}
