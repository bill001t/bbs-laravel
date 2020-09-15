<?php
Wind::import('APPS:u.service.helper.PwUserHelper');
/**
 * @author xiaoxia.xu <xiaoxia.xuxx@aliyun-inc.com> 
 * @link http://www.phpwind.com
 * @copyright Copyright ©2003-2010 phpwind.com
 * @license
 */

class UErrorController extends PwErrorController {
	/** 
	 * 用户注册信息错误
	 * 
	 * @return void
	 */
	public function regErrorAction(Request $request) {
		/*if (in_array('register', Core::C('verify', 'showverify'))) {
			->with('verify');
		}
		$config = Core::C('register');

		$userForm = $request->get('pwUserRegisterForm');

		$this->setTemplatePath('TPL:u');
		$this->setTemplateExt('htm');

		return view('register')
			->with($config, 'config')
		->with(PwUserHelper::getRegFieldsMap(), 'needFields')
		->with(array('location', 'hometown'), 'selectFields')
		->with($this->error, 'message')
			->with($userForm->getData(), 'data')
		->with($this->state, 'state');*/
	}
	
	/** 
	 * 用户注册信息错误
	 * 
	 * @return void
	 */
	public function loginErrorAction(Request $request) {

		/*$userForm = $request->get('pwUserLoginForm');

		$this->setTemplatePath('TPL:u');
		$this->setTemplateExt('htm');
		return view('login')
            ->with($this->state, 'state')
        ->with($this->error, 'message')
            ->with($userForm->getData(), 'data');*/
	}
}