<?php

namespace App\Utils;

class JsonHelper
{
    public static function parseJsonToCollection(?string $json)
    {
        $parsed = json_decode($json, true);
        return collect($parsed);
    }

    public static function parseJsonToObject(?string $json)
    {
        return json_decode($json, true);
    }
}
