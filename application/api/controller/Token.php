<?php
namespace app\api\controller;

use app\api\validate\TokenGet;

class Token 
{
    public function getToken($code='') {
    	(new TokenGet())->goCheck();
    	
    }
    
}