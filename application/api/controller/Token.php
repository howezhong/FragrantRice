<?php
namespace app\api\controller;

use app\api\service\UserToken;
use app\api\validate\TokenGet;

class Token 
{
    public function getToken($code='') {
    	(new TokenGet())->goCheck();
    	$ut = new UserToken($code); // 放到这是因为通过构造函数获取这个code的
    	$token = $ut->get(); // 字符串
    	return json(['token'=>$token]);
    }
    
}