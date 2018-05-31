<?php
namespace app\api\validate;

use think\Request;
use think\Validate;
use app\lib\exception\ParameterException;

class BaseValidate extends Validate
{
	public function goCheck() {
		// 获取https传入的参数  对这些参数进行校验
		$request = Request::instance();
		$params = $request->param();
		if (!$this->check($params)) {
			$exception = new ParameterException([
            	'msg' => is_array($this->error) ? implode(';', $this->error) : $this->error,
            ]);
            throw $exception;
		}
		return true;
	}
	/**
	 * 验证ID
	 * @param  int  $value ID
	 * @param  string  $rule  [description]
	 * @param  string  $data  [description]
	 * @param  string  $field [description]
	 * @return boolean        布尔值
	 */
	protected function isPositiveInteger($value, $rule='', $data='', $field='') {
		if (is_numeric($value) && is_int($value + 0) && ($value + 0) > 0) {
			return true;
		}
		return $field . '必须是正整数';
	}

    protected function isNotEmpty($value, $rule='', $data='', $field='') {
        if (empty($value)) {
            return $field . '不允许为空';
        } else {
            return true;
        }
    }

    /**
     * @param array $arrays 通常传入request.post变量数组
     * @return array 按照规则key过滤后的变量数组
     * @throws ParameterException
     */
    public function getDataByRule($arrays) {
        if (array_key_exists('user_id', $arrays) | array_key_exists('uid', $arrays)) {
            // 不允许包含user_id或者uid，防止恶意覆盖user_id外键
            throw new ParameterException([
                'msg' => '参数中包含有非法的参数名user_id或者uid'
            ]);
        }
        // 根据规则获取数据，用户多传的不要
        $newArray = [];
        foreach ($this->rule as $key => $value) {
            $newArray[$value[0]] = $arrays[$value[0]];
        }
        return $newArray;
    }

    //没有使用TP的正则验证，集中在一处方便以后修改
    //不推荐使用正则，因为复用性太差
    //手机号的验证规则
    protected function isMobile($value) {
        $rule = '^1(3|4|5|7|8)[0-9]\d{8}$^';
        $result = preg_match($rule, $value);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
}
