<?php

namespace app\lib\exception;

class ForbiddenException extends BaseException
{
    public $code = 403; // 权限不够的意思
    public $msg = '权限不够';
    public $errorCode = 10001;
}