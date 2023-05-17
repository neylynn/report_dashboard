<?php

namespace App\Traits\Utilities;

use DateTime;

trait DateTimeFormatter
{
    function timeStampToDateTime($timeStamp)
    {
        return date("Y-m-d H:i:s", $timeStamp);
    }

    function timeStampToDate($timeStamp)
    {
        return date("Y-m-d", $timeStamp);
    }

    function dateTimeToDate($dateTime)
    {
        return DateTime::createFromFormat('Y-m-d H:i:s', $dateTime)->format('Y-m-d');
    }
}
