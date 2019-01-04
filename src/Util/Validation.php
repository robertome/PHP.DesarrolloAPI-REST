<?php
/**
 * Created by PhpStorm.
 * User: rmartine
 * Date: 03/01/2019
 * Time: 15:01
 */

namespace App\Util;


use App\Exception\ArgumentNotValidException;

class Validation
{
    public static function notNull($property, string $message)
    {
        if ($property === null) {
            throw new ArgumentNotValidException($message . " is missing");
        }
    }

    public static function notBlank($property, string $message)
    {
        self::notNull($property, $message);

        $strTemp = $property;
        $strTemp = trim($strTemp);
        if (strlen($strTemp) == 0) {
            throw new ArgumentNotValidException($message . " is missing");
        }
    }

}