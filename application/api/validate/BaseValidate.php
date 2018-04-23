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
		$params = $request->param();//dump($params);die;
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
}
