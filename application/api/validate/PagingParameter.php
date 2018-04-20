<?php
namespace app\api\validate;

class PagingParameter extends BaseValidate
{
    protected $rule = [
    	['page','isPositiveInteger','分页参数必须是正整数'],
    	['size','isPositiveInteger','分页参数必须是正整数']
    ];
}
