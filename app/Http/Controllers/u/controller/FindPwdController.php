<?php
Wind::import('SRV:user.srv.PwFindPassword');
Wind::import('APPS:u.service.helper.PwUserHelper');
Wind::import('SRV:user.validator.PwUserValidator');
/**
 * 重置密码流程
 * 重置成功一次  才算找回密码次数完成一次，才会更新验证码状态及找回密码次数
 *
 * @author xiaoxia.xu <xiaoxia.xuxx@aliyun-inc.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: FindPwdController.php 22230 2012-12-19 21:45:20Z xiaoxia.xuxx $
 * @package src.products.user.controller
 */
class FindPwdController extends Controller{
	private $isMailOpen = false;
	private $isMobileOpen = false;

	/* (non-PHPdoc)
	 * @see PwBaseController::beforeAction()
	 */
	public function __construct()
    {
        $this->middleware('login');

        $this->isMailOpen = Core::C('email', 'mailOpen') ? true : false;
        $this->isMobileOpen = Core::C('login', 'mobieFindPasswd') ? true : false;
    }

   /* public function beforeAction($handlerAdapter) {
		parent::beforeAction($handlerAdapter);
		if ($this->loginUser->isExists()) return redirect('bbs/index/run'));
		$this->isMailOpen = Core::C('email', 'mailOpen') ? true : false;
		$this->isMobileOpen = Core::C('login', 'mobieFindPasswd') ? true : false;
	}*/

	/* (non-PHPdoc)
	 * @see WindController::run()
	 */
	public function run() {
		if (false === ($this->isMailOpen || $this->isMobileOpen)) {
			return view('findpwd_close');
		}
	}
	
	/**
	 * 检查用户密码
	 */
	public function checkUsernameAction(Request $request) {
		$username = $request->get('username');
		if (!$username) {
			return $this->showError('USER:findpwd.username.require', 'u/findPwd/run');
		}
		
		/*用户不存在*/
		if (!PwUserValidator::checkUsernameExist($username)) return $this->showError('USER:user.error.-14');
		$findPasswordBp = new PwFindPassword($username);
		
		/*[用户分支1：没有绑定任何可以找回密码的方式]*/
		if (false === ($findPasswordBp->isBindMail() || $findPasswordBp->isBindMobile())) {
			return $this->showError('USER:findpwd.notbind');
		}
		$isOverMail = $findPasswordBp->isOverByMail();
		$isOverMobile = $findPasswordBp->isOverByMobile();
		/*[用户分支2：两种方式的找回密码都已经超过当日次数限制]*/
		if ($isOverMail && $isOverMobile) {
			return $this->showError('USER:findpwd.over.limit');
		}
		
		/*【分支1：只开通手机】网站开通了：手机找回密码方式*/
		if (false === $this->isMailOpen && $this->isMobileOpen) {
			if ($isOverMobile) return $this->showError('USER:findpwd.over.limit.mobile');
			if ($request->get('step', 'post') == 'do') {
				return redirect('u/findPwd/bymobile?username=' . $username);
			}
			return $this->showMessage();
		}
		/*【分支2：只开通邮箱】网站开通了：邮箱找回密码方式*/
		if (false === $this->isMobileOpen && $this->isMailOpen) {
			if ($isOverMail) return $this->showError('USER:findpwd.over.limit.email');
			if ($request->get('step', 'post') == 'do') {
				return redirect('u/findPwd/bymail?username=' . $username);
			}
			return $this->showMessage();
		}
		/*【分支3：都关闭】网站关闭找回密码方式*/
		if (false === ($this->isMobileOpen || $this->isMailOpen)) {
			return $this->showError('USER:findpwd.way.close');
			if ($request->get('step', 'post') == 'do') {
				return redirect('u/findPwd/bymail?username=' . $username);
			}
			return $this->showMessage();
		}
		
		/*【分支4：都开通】网站开通了：手机找回密码和邮箱找回密码方式*/
		/*[分支4.1：用户只绑定了手机找回密码方式]*/
		if (false === $findPasswordBp->isBindMail() && $findPasswordBp->isBindMobile()) {
			if ($isOverMobile) {
				return $this->showError('USER:findpwd.over.limit.mobile');
			}
			if ($request->get('step', 'post') == 'do') {
				return redirect('u/findPwd/bymobile?username=' . $username);
			}
			return $this->showMessage();
		}
		/*[分支4.2： 用户只绑定了邮箱找回密码方式]*/
		if (false === $findPasswordBp->isBindMobile() && $findPasswordBp->isBindMail()) {
			if ($isOverMail) {
				return $this->showError('USER:findpwd.over.limit.email');
			}
			if ($request->get('step', 'post') == 'do') {
				return redirect('u/findPwd/bymail?username=' . $username);
			}
			return $this->showMessage();
		}
		
		/*[分支4.3：用户都绑定了两种方式]*/
		/*网站支持两种方式找回密码*/
		if ($request->get('step', 'post') == 'do') {

			return view('findpwd_way')->with($username, 'username');
		} else {
			return $this->showMessage('');
		}
	}
	
	/**
	 * 通过邮箱找回密码
	 */
	public function bymailAction(Request $request) {
		$username = $request->get('username');
		if (!$username) {
			return $this->showError('USER:findpwd.username.require', 'u/findPwd/run');
		}
		if (!$this->isMailOpen) {
			return $this->showError('USER:findpwd.way.email.close', 'u/findPwd/run');
		}
		$findPasswordBp = new PwFindPassword($username);
		/*->with($findPasswordBp->getFuzzyEmail(), 'mayEmail');
		->with(in_array('resetpwd', Core::C('verify', 'showverify')), 'verify');
		->with($username, 'username');
		->with(2, 'step');*/

        /*???模板是什么？*/
	}
	
	/**
	 * 检查有效是否正确
	 */
	public function dobymailAction(Request $request) {
		if (!$this->isMailOpen) {
			return $this->showError('USER:findpwd.way.email.close', 'u/findPwd/run');
		}
		list($username, $email, $code) = $request->only(['username', 'email', 'code']);
		$this->checkCode($code);
		/*检查邮箱是否正确*/
		$findPasswordBp = new PwFindPassword($username);
		if (true !== ($result = $findPasswordBp->checkEmail($email))) {
			return $this->showError($result->getError());
		}
		/*发送重置邮件*/
		if (!$findPasswordBp->sendResetEmail(PwFindPassword::createFindPwdIdentify($username, PwFindPassword::WAY_EMAIL, $email))) {
			return $this->showError('USER:findpwd.error.sendemail');
		}
		

		return view('findpwd_bymail')
            ->with($username, 'username')
        ->with($findPasswordBp->getEmailUrl(), 'emailUrl')
        ->with(3, 'step');
	}
	
	/**
	 * 通过手机号码找回密码
	 */
	public function bymobileAction(Request $request) {
		$username = $request->get('username');
		if (!$username) {
			return $this->showError('USER:findpwd.username.require', 'u/findPwd/run');
		}
		if (!$this->isMobileOpen) {
			return $this->showError('USER:findpwd.way.mobile.close', 'u/findPwd/run');
		}
		/*->with(in_array('resetpwd', Core::C('verify', 'showverify')), 'verify');
		->with($username, 'username');
		->with(2, 'step');*/
	}
	
	/**
	 * 验证手机验证码
	 */
	public function checkmobilecodeAction(Request $request) {
		if (!$this->isMobileOpen) {
			return $this->showError('USER:findpwd.way.mobile.close', 'u/findPwd/run');
		}
		list($username, $mobileCode, $mobile) = $request->get(array('username', 'mobileCode', 'mobile'), 'post');
		!PwUserValidator::isMobileValid($mobile) && return $this->showError('USER:error.mobile', 'u/findPwd/run');
		!$mobileCode && return $this->showError('USER:mobile.code.empty', 'u/findPwd/run');

		$userInfo = $this->_getUserDs()->getUserByName($username, PwUser::FETCH_INFO);
		if ($userInfo['mobile'] != $mobile) {
			return $this->showError('USER:findpwd.error.mobile');
		}
		if (($mobileCheck = app('mobile.srv.PwMobileService')->checkVerify($mobile, $mobileCode)) instanceof ErrorBag) {
			return $this->showError($mobileCheck->getError());
		}
		
		$statu = PwFindPassword::createFindPwdIdentify($username, PwFindPassword::WAY_MOBILE, $mobile);
		return $this->showMessage('success','u/findPwd/resetpwd?way=mobile&_statu='.$statu.'&mobile='.$mobile.'&mobileCode='.$mobileCode);
	}

	/**
	 * 验证邮件展示重置密码页面
	 */
	public function resetpwdAction(Request $request) {
		list($userinfo, $value, $type, $statu) = $this->checkState();
		$code = $request->get('code', 'get');
		$findPasswordBp = new PwFindPassword($userinfo['username']);
		if ($type == PwFindPassword::WAY_EMAIL) {
			if ($findPasswordBp->isOverByMail()) {
				return $this->showError('USER:findpwd.over.limit.email');
			}
			if (($result = $findPasswordBp->checkResetEmail($value, $code)) instanceof ErrorBag) {
				return $this->showError($result->getError());
			}
		}
		if ($type == PwFindPassword::WAY_MOBILE) {
			if ($findPasswordBp->isOverByMobile()) {
				return $this->showError('USER:findpwd.over.limit.mobile');
			}
			list($mobile, $mobileCode) = $request->get(array('mobile', 'mobileCode'), 'get');
			if (($mobileCheck = app('mobile.srv.PwMobileService')->checkVerify($mobile, $mobileCode)) instanceof ErrorBag) {
				return $this->showError($mobileCheck->getError());
			}
		}
		$resource = app(MessageTool::class);
		list($_pwdMsg, $_pwdArgs) = PwUserValidator::buildPwdShowMsg();

		return view('findpwd_resetpwd')
            ->with($resource->getMessage($_pwdMsg, $_pwdArgs), 'pwdReg')
        ->with($userinfo['username'], 'username')
        ->with($statu, 'statu');
	}
	
	/**
	 * 重置密码
	 */
	public function doresetpwdAction(Request $request) {
		if ($request->get('step', 'post') == 'end') {
			list($userInfo, $value, $type) = $this->checkState();
			list($password, $repassword) = $request->get(array('password', 'repassword'), 'post');
			if ($password != $repassword) return $this->showError('USER:user.error.-20');
			$userDm = new PwUserInfoDm($userInfo['uid']);
			$userDm->setUsername($userInfo['username']);
			$userDm->setPassword($password);
			$userDm->setQuestion('', '');
			/* @var $userDs PwUser */
			$userDs = app('user.PwUser');
			$result = $userDs->editUser($userDm, PwUser::FETCH_MAIN);
			if ($result instanceof ErrorBag) {
				return $this->showError($result->getError());
			} else {
				//检查找回密码次数及更新
				$findPasswordBp = new PwFindPassword($userInfo['username']);
				$findPasswordBp->success($type);
			}
			return $this->showMessage('USER:findpwd.success', 'u/login/run?backurl=' . 'bbs/index/run');
		}
	}
	
	/**
	 * 发送手机验证码
	 */
	public function sendmobileAction(Request $request) {
		list($mobile, $username) = $request->get(array('mobile', 'username'), 'post');
		if (($result = $this->_checkMobileRight($mobile, $username)) instanceof ErrorBag) {
			return $this->showError($result->getError());
		}
		if (($result = app('SRV:mobile.srv.PwMobileService')->sendMobileMessage($mobile)) instanceof ErrorBag) {
			return $this->showError($result->getError());
		}
		return $this->showMessage('success');
	}
	
	/**
	 * 验证手机号码
	 */
	public function checkmobileAction(Request $request) {
		list($mobile, $username) = $request->get(array('mobile', 'username'), 'post');
		if (($result = $this->_checkMobileRight($mobile, $username)) instanceof ErrorBag) {
			return $this->showError($result->getError());
		}
		$result = app('SRV:mobile.srv.PwMobileService')->checkTodayNum($mobile);
		if ($result instanceof ErrorBag) {
			return $this->showError($result->getError());
		}
		return $this->showMessage();
	}
	
	private function _checkMobileRight($mobile, $username) {
		if (!$this->isMobileOpen) {
			return new ErrorBag('USER:mobile.findPwd.open.error');
		}
		Wind::import('SRV:user.validator.PwUserValidator');
		if (!PwUserValidator::isMobileValid($mobile)) {
			return new ErrorBag('USER:error.mobile');
		}
		$userInfo = $this->_getUserDs()->getUserByName($username, PwUser::FETCH_INFO);
		if ($userInfo['mobile'] != $mobile) {
			return new ErrorBag('USER:findpwd.error.mobile');
		}
		return true;
	}
	
	/**
	 * 检查邮箱地址合法性
	 */
	public function checkMailFormatAction(Request $request) {
		if (!CommonValidator::isEmail($request->get('email', 'post'))) {
			return $this->showError('USER:user.error.-7');
		} else {
			return $this->showMessage();
		}
	}
	
	/**
	 * 检查手机号码格式是否正确
	 */
	public function checkPhoneFormatAction(Request $request) {
		if (!PwUserValidator::isMobileValid($request->get('phone', 'post'))) {
			return $this->showError('USER:mobile.error.formate');
		} else {
			return $this->showMessage();
		}
	}
	
	/**
	 * 检查是否符合要求
	 * @param string $type 类型
	 */
	private function checkState(Request $request) {
		$statu = $request->get('_statu', 'get');
		!$statu && $statu = $request->get('statu', 'post');
		if (!$statu) return $this->showError('USER:illegal.request');
		list($username, $way, $value) = PwFindPassword::parserFindPwdIdentify($statu);
		$userInfo = $this->_getUserDs()->getUserByName($username, PwUser::FETCH_INFO | PwUser::FETCH_MAIN);
		if ($userInfo[PwFindPassword::getField($way)] != $value) {
			return redirect('u/findPwd/run', array(), true);
		}
		return array($userInfo, $value, $way, $statu);
	}
	
	/**
	 * 检查验证码
	 *
	 * @param string $code
	 * @return boolean
	 */
	private function checkCode($code) {
		if (!in_array('resetpwd', Core::C('verify', 'showverify'))) return true;
		/*验证码检查*/
		/* @var $verifySrv PwCheckVerifyService */
		$verifySrv = app("verify.srv.PwCheckVerifyService");
		if ($verifySrv->checkVerify($code) !== true) {
			return $this->showError('USER:verifycode.error');
		}
		return true;
	}
	
	/**
	 * 获得用户的DS
	 *
	 * @return PwUser
	 */
	private function _getUserDs() {
		return app('user.PwUser');
	}
	
	/* (non-PHPdoc)
	 * @see WindSimpleController::setDefaultTemplateName()
	 */
	protected function setDefaultTemplateName($handlerAdapter) {
		return view(strtolower($handlerAdapter->getController()) . '_' . $handlerAdapter->getAction());
	}
}