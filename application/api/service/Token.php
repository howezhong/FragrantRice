<?php
namespace app\api\service;

use app\lib\enum\ScopeEnum;
use app\lib\exception\ForbiddenException;
use app\lib\exception\ParameterException;
use app\lib\exception\TokenException;
use think\Cache;
use think\Exception;
use think\Request;

class Token
{
    public static function generateToken() {
        // 32个字符组成一组随机字符串
        $randChar = getRandChar(32);
        // 用三组字符串，进行md5加密
        $timestamp = $_SERVER['REQUEST_TIME_FLOAT'];
        // 盐
        $tokenSalt = config('secure.token_salt');
        return md5($randChar . $timestamp . $tokenSalt);
    }

    /**
     * 当需要获取全局UID时，应当调用此方法，而不应当自己解析UID
     */
    public static function getCurrentUid()
    {
        $uid = self::getCurrentTokenVar('uid');
        $scope = self::getCurrentTokenVar('scope');
        if ($scope == ScopeEnum::Super) {
            // 只有Super权限才可以自己传入uid，且必须在get参数中，post不接受任何uid字段
            $userID = input('get.uid');
            if (!$userID) {
                throw new ParameterException( [
                    'msg' => '没有指定需要操作的用户对象'
                ]);
            }
            return $userID;
        } else {
            return $uid;
        }
    }
    
    /**
     * 获取UID
     * @param $key
     * @return mixed
     * @throws Exception
     * @throws TokenException
     */
    public static function getCurrentTokenVar($key) {
        // 从http请求头里拿到token
        $token = Request::instance()->header('token');
        $vars = Cache::get($token);
        if (!$vars) {
            throw new TokenException();
        } else {
            if(!is_array($vars)) {
                $vars = json_decode($vars, true);
            }
            if (array_key_exists($key, $vars)) {
                return $vars[$key];
            } else {
                throw new Exception('尝试获取的Token变量并不存在');
            }
        }
    }

    //验证token是否合法或者是否过期
    //验证器验证只是token验证的一种方式
    //另外一种方式是使用行为拦截token，根本不让非法token
    //进入控制器
    public static function needPrimaryScope()
    {
        $scope = self::getCurrentTokenVar('scope');
        if ($scope) {
            if ($scope >= ScopeEnum::User) {
                return true;
            }
            else{
                throw new ForbiddenException();
            }
        } else {
            throw new TokenException();
        }
    }


    // 用户专有权限
    public static function needExclusiveScope()
    {
        $scope = self::getCurrentTokenVar('scope');
        if ($scope){
            if ($scope == ScopeEnum::User) {
                return true;
            } else {
                throw new ForbiddenException();
            }
        } else {
            throw new TokenException();
        }
    }
}