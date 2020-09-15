<?php

/**
 * 手机验证码
 *
 * @author jinlong.panjl <jinlong.panjl@aliyun-inc.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id$
 * @package wind
 */
class MobileController extends Controller{

	/* (non-PHPdoc)
	 * @see PwBaseController::beforeAction()
	 */
	public function beforeAction($handlerAdapter) {
		parent::beforeAction($handlerAdapter);
		
	}
	
	/**
	 * 验证手机验证码
	 */
	public function checkmobilecodeAction(Request $request) {
		list($mobile, $mobileCode) = $request->get(array('mobile', 'mobileCode'), 'post');
		if (($result = $this->_getService()->checkVerify($mobile, $mobileCode)) instanceof ErrorBag) {
			return $this->showError($result->getError());
		}
		return $this->showMessage('fail');
	}
	
	/**
	 * PwMobileService
	 *
	 * @return PwMobileService
	 */
	private function _getService() {
		return app('mobile.srv.PwMobileService');
	}
}