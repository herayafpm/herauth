<?php

namespace Raydragneel\Herauth\Libraries;

class ClaEncrypter
{
    public static function encrypt($string = '')
    {
        $encrypter = service('encrypter');
        return base64_encode($encrypter->encrypt($string));
    }
    public static function decrypt($string = '')
    {
        $encrypter = service('encrypter');
        return $encrypter->decrypt(base64_decode($string));
    }
}