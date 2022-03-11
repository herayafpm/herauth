<?php namespace Raydragneel\Herauth\Libraries;

use MessageFormatter;

class Str{
    public static function format($message = '',$args = [])
    {
        $request = service('request');
        return MessageFormatter::formatMessage($request->getLocale(),$message, $args);
    }
}