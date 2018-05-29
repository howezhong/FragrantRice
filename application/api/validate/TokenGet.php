<?php
namespace app\api\validate;

class TokenGet extends BaseValidate
{
	protected $rule = [
        'code' => 'require|isNotEmpty'
    ];
    // require 不代表不能为空
    protected $message=[
        'code' => 'code参数必须'
    ];
}
