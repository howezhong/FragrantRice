<?php
namespace app\api\service;

class Token
{
    public static function generteToken() {
        // 32个字符组成一组随机字符串
        $randChar = getRandChar(32);
        // 用三组字符串，进行md5加密
        $timestamp = $_SERVER['REQUEST_TIME_FLOAT'];
        // 盐
        $tokenSalt = config('secure.token_salt');
        return md5($randChar . $timestamp . $tokenSalt);
    }
}