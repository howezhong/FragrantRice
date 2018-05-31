<?php
namespace app\api\controller;

use think\Controller;
use app\api\service\Token;

class Base extends Controller
{
    /**
     * 检查权限
     */
    protected function checkPrimaryScope() {
        Token::needPrimaryScope();
    }

    protected function checkSuperScope() {
        Token::needSuperScope();
    }

    protected function checkExclusiveScope() {
        Token::needExclusiveScope();
    }
}