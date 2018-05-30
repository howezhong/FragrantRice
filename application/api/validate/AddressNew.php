<?php

namespace app\api\validate;


class AddressNew extends BaseValidate
{
    // 为防止欺骗重写user_id外键
    // rule中严禁使用user_id
    // 获取post参数时过滤掉user_id
    // 所有数据库和user关联的外键统一使用user_id，而不要使用uid
    protected $rule = [
        ['name','require|isNotEmpty','收获人姓名必填'],
        ['mobile','require|isMobile','手机号码必填|手机号码格式不正确'],
        ['province','require|isNotEmpty','省名必填'],
        ['city','require|isNotEmpty','市名必填'],
        ['country','require|isNotEmpty','区域必填'],
        ['detail','require|isNotEmpty','详细地址必填']
    ];
}