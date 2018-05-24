<?php
namespace app\api\service;

class UserToken extends Token
{
	protected $code;
	protected $wxAppID;
	protected $wxAppSecret;
	protected $wxLoginUrl;

    /**
     * 拼接URL
     * @param string $code
     */
	public function __construct($code){
		$this->code = $code;
        $this->wxAppID = config('wx.app_id');
        $this->wxAppSecret = config('wx.app_secret');
        // sprintf() 函数把格式化的字符串写入一个变量中
        $this->wxLoginUrl = sprintf(
            config('wx.login_url'), $this->wxAppID, $this->wxAppSecret, $this->code);
	}

	/**
	 * 获取session_key
	 * @param  string $code 
	 * @return [type]       
	 */
	public function get($code){
		$result =curl_get($this->wxLoginUrl);
		$wxResult = json_decode( $result, true );
		if (empty($wxResult)) {
			throw new Exception("获取session_key及openID时异常，微信内部错误");
		} else {
			$loginFail = array_key_exists('errcode', $wxResult);
			if ($loginFail) {
				$this->processLoginError($wxResult);
			} else {
				$this->grantToken();
			}
		}
	}

	private function processLoginError($wxResult){
		throw new WxChatException([
			'msg' => $wxResult['errmsg'],
			'errorCode' => $wxResult['errcode']
		]);
	}
	
	// 颁发令牌
    // 只要调用登陆就颁发新令牌
    // 但旧的令牌依然可以使用
    // 所以通常令牌的有效时间比较短
    // 目前微信的express_in时间是7200秒
    // 在不设置刷新令牌（refresh_token）的情况下
    // 只能延迟自有token的过期时间超过7200秒（目前还无法确定，在express_in时间到期后
    // 还能否进行微信支付
    // 没有刷新令牌会有一个问题，就是用户的操作有可能会被突然中断
    private function grantToken($wxResult)
    {
    	/**
    	 * 拿到openid后到数据库里看一下，这个openid是不是已经存在
    	 * 如果存在则不处理，如果不存在那么新增一条user记录
    	 * 生成令牌，准备缓存数据，写入缓存
    	 * 把令牌返回到客户端去
    	 * 
    	 * 此处生成令牌使用的是TP5自带的令牌
    	 * 如果想要更加安全可以考虑自己生成更复杂的令牌
    	 * 比如使用JWT并加入盐，如果不加入盐有一定的几率伪造令牌
    	 * $token = Request::instance()->token('token', 'md5');
    	 */
        $openid = $wxResult['openid'];
        $user = User::getByOpenID($openid);
        if (!$user){
            // 借助微信的openid作为用户标识
            // 但在系统中的相关查询还是使用自己的uid
            $uid = $this->newUser($openid);
        } else {
            $uid = $user->id;
        }
        $cachedValue = $this->prepareCachedValue($wxResult, $uid);
        $token = $this->saveToCache($cachedValue);
        return $token;
    }

    // 写入缓存
    private function saveToCache($wxResult) {
        $key = self::generateToken();
        $value = json_encode($wxResult);
        // 缓存时间
        $expire_in = config('setting.token_expire_in');
        $result = cache($key, $value, $expire_in);

        if (!$result){
            throw new TokenException([
                'msg' => '服务器缓存异常',
                'errorCode' => 10005
            ]);
        }
        return $key; // 此就是令牌
    }
    /**
     * @param $wxResult
     * @param $uid
     * @return mixed
     */
    private function prepareCachedValue($wxResult, $uid)
    {
        $cachedValue = $wxResult;
        $cachedValue['uid'] = $uid;
        $cachedValue['scope'] = ScopeEnum::User;
        return $cachedValue;
    }

    // 创建新用户,写入一条数据
    private function newUser($openid)
    {
        // 有可能会有异常，如果没有特别处理
        // 这里不需要try——catch
        // 全局异常处理会记录日志
        // 并且这样的异常属于服务器异常
        // 也不应该定义BaseException返回到客户端
        $user = User::create(['openid' => $openid]);
        return $user->id;
    }
}