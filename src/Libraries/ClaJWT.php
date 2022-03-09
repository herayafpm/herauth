<?php

namespace Raydragneel\Herauth\Libraries;

use DateTimeImmutable;
use \Firebase\JWT\JWT;

class ClaJWT
{
    public static function encode($data, $expireAt = null, $withExpiredToken = false, $withRefreshToken = false)
    {
        $config = config("Herauth");
        $private_key = config('encryption')->key;
        $issuedAt   = new DateTimeImmutable();
        $expireDateTime = new DateTimeImmutable($expireAt ?? "now");
        $expire     = ($expireAt) ? $expireDateTime : $issuedAt->modify("{$config->sym}{$config->duration} {$config->unit}");
        $token_data = [];
        $token_data = [
            'iat' => $issuedAt->getTimestamp(),
            'nbf' => $issuedAt->getTimestamp(),
        ];
        if ($withExpiredToken) {
            $token_data['exp'] = $expire->getTimestamp();
        }
        $token_data = array_merge($data, $token_data);
        $access_token = JWT::encode($token_data, $private_key, 'HS256');
        $ret_data = [
            'access_token' => $access_token,
        ];
        if ($withExpiredToken) {
            $ret_data['access_token_expired'] = $expire->format("Y-m-d H:i:s");
        }
        if ($withRefreshToken) {
            $refresh_token = static::encode($data, $expireDateTime->modify("{$config->symRefresh}{$config->durationRefresh} {$config->unitRefresh}")->format("Y-m-d H:i:s"), $withExpiredToken);
            $ret_data['refresh_token'] = $refresh_token['access_token'];
            $ret_data['refresh_token_expired'] = $refresh_token['access_token_expired'];
        }
        return $ret_data;
    }
    public static function decode($jwt)
    {
        $config = config("Herauth");
        $public_key = config('encryption')->key;
        $jwt = JWT::decode($jwt, $public_key, ['HS256']);
        return $jwt;
    }
}