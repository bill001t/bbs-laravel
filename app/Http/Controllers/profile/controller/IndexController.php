<?php
Wind::import('APPS:.profile.controller.BaseProfileController');
Wind::import('SRV:user.srv.PwUserProfileService');
Wind::import('SRV:user.validator.PwUserValidator');
Wind::import('SRV:user.PwUserBan');
Wind::import('APPS:profile.service.PwUserProfileExtends');
		
/**
 * 用户资料页面
 *
 * @author xiaoxia.xu <xiaoxia.xuxx@aliyun-inc.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: IndexController.php 28946 2013-05-31 04:59:50Z jieyin $
 * @package src.products.u.controller.profile
 */
class IndexController extends BaseProfileController {
	
	/* (non-PHPdoc)
	 * @see BaseProfileController::beforeAction()
	 */
	public function beforeAction($handlerAdapter) {
		parent::beforeAction($handlerAdapter);
		$this->setCurrentLeft('profile');
	}

	/* (non-PHPdoc)
	 * @see WindController::run()
	 */
	public function run() {
		$userInfo = app('user.PwUser')->getUserByUid($this->loginUser->uid, PwUser::FETCH_INFO);
		$userInfo = array_merge($this->loginUser->info, $userInfo);
		list($year, $month, $day) = PwUserHelper::getBirthDay();
		
		->with($this->_buildArea($userInfo['location']), 'location');
		->with($this->_buildArea($userInfo['hometown']), 'hometown');
		
		$isAllowSign = false;
		if ($this->loginUser->getPermission('allow_sign')) {
			$isAllowSign = true;
			$isSignBan = false;
			if (Tool::getstatus($this->loginUser->info['status'], PwUser::STATUS_BAN_SIGN)) {
				Wind::import('SRV:user.srv.PwBanBp');
				$banBp = new PwBanBp($this->loginUser->uid);
				if (false === $banBp->checkIfBanSign()) {
					$banBp->recoveryBanSignError();
				} elseif ($banBp->endDateTimeBanSign()) {
					$s = 1 << (PwUser::STATUS_BAN_SIGN - 1);
					$this->loginUser->info['status'] = $this->loginUser->info['status'] - $s;
				} else {
					$isSignBan = true;
				}
			}
		}
		$extendsSrv = new PwUserProfileExtends($this->loginUser);
		list($_left, $_tab) = $this->getMenuService()->getCurrentTab($request->get('_left'), $request->get('_tab'));
		$extendsSrv->setCurrent($_left, $_tab);
		$this->runHook('c_profile_foot_run', $extendsSrv);
		->with($extendsSrv, 'hookSrc');
		
		->with($isAllowSign, 'isAllowSign');
		->with($isSignBan, 'isSignBan');
		->with($this->loginUser->getPermission('sign_max_length'), 'signMaxLength');
		->with($year, 'years');
		->with($month, 'months');
		->with($day, 'days');
		->with($userInfo, 'userinfo');
		
		// seo设置
		Wind::import('SRV:seo.bo.PwSeoBo');
		$seoBo = PwSeoBo::getInstance();
		$lang = Wind::getComponent('i18n');
		$seoBo->setCustomSeo($lang->getMessage('SEO:profile.index.run.title'), '', '');
		Core::setV('seo', $seoBo);
	}
	
	/** 
	 * 编辑用户信息
	 */
	public function dorunAction(Request $request) {
		$request->isPost() || return $this->showError('operate.fail');

		$userDm = new PwUserInfoDm($this->loginUser->uid);
		$userDm->setRealname($request->get('realname', 'post'));
		$userDm->setByear($request->get('byear', 'post'));
		$userDm->setBmonth($request->get('bmonth', 'post'));
		$userDm->setBday($request->get('bday', 'post'));
		$userDm->setGender($request->get('gender', 'post'));
		$userDm->setHomepage($request->get('homepage', 'post'));
		$userDm->setProfile($request->get('profile', 'post'));
		
		list($hometown, $location) = $request->get(array('hometown', 'location'), 'post');

		$srv = WindidApi::api('area');
		$areas = $srv->fetchAreaInfo(array($hometown, $location));
		$userDm->setHometown($hometown, isset($areas[$hometown]) ? $areas[$hometown] : '');
		$userDm->setLocation($location, isset($areas[$location]) ? $areas[$location] : '');
		
		//没有禁止签名的时候方可编辑签名
		if ($this->loginUser->getPermission('allow_sign')) {
			$bbsSign = $request->get('bbs_sign', 'post');
			if (($len = $this->loginUser->getPermission('sign_max_length')) && Tool::strlen($bbsSign) > $len) { //仅在此限制签名字数
				return $this->showError(array('USER:user.edit.sign.length.over', array('{max}' => $len)));
			}
			Wind::import('LIB:ubb.PwUbbCode');
			Wind::import('LIB:ubb.config.PwUbbCodeConvertConfig');
			$ubb = new PwUbbCodeConvertConfig();
			$ubb->isConverImg = $this->loginUser->getPermission('sign_ubb_img') ? true : false;
			$userDm->setBbsSign($bbsSign)
				->setSignUseubb($bbsSign != PwUbbCode::convert($bbsSign, $ubb) ? 1 : 0);
		}
		
		$result = $this->_editUser($userDm, PwUser::FETCH_MAIN + PwUser::FETCH_INFO);
		if ($result instanceof ErrorBag) {
			return $this->showError($result->getError());
		} else {
			$this->loginUser->info = array_merge($this->loginUser->info, $userDm->getData());
			return $this->showMessage('USER:user.edit.profile.success');
		}
	}
	
	/**
	 * 联系方式
	 */
	public function contactAction(Request $request) {
		$userInfo = app('user.PwUser')->getUserByUid($this->loginUser->uid, PwUser::FETCH_INFO);
		$extendsSrv = new PwUserProfileExtends($this->loginUser);
		list($_left, $_tab) = $this->getMenuService()->getCurrentTab($request->get('_left'), $request->get('_tab'));
		$extendsSrv->setCurrent($_left, $_tab);
		$this->runHook('c_profile_foot_run', $extendsSrv);
		->with($extendsSrv, 'hookSrc');
		->with($userInfo, 'userinfo');
	}
	
	/** 
	 * 编辑联系方式
	 */
	public function docontactAction(Request $request) {
		$request->isPost() || return $this->showError('operate.fail');

		$userDm = new PwUserInfoDm($this->loginUser->uid);
		$userDm->setTelphone($request->get('telphone', 'post'));
		$userDm->setAddress($request->get('address', 'post'));
		$userDm->setZipcode($request->get('zipcode', 'post'));
		$userDm->setAliww($request->get('aliww', 'post'));
		$userDm->setQq($request->get('qq', 'post'));
		$userDm->setMsn($request->get('msn', 'post'));
		list($alipay, $mobile) = $request->get(array('alipay', 'mobile'), 'post');
		if ($alipay) {
			$r = PwUserValidator::isAlipayValid($alipay, $this->loginUser->username);
			if ($r instanceof ErrorBag) return $this->showError($r->getError());
		}
		if ($mobile) {
			$r = PwUserValidator::isMobileValid($mobile);
			if ($r instanceof ErrorBag) return $this->showError($r->getError());
		}
		if ($email) {
			$r = PwUserValidator::isEmailValid($email, $this->loginUser->username);
			if ($r instanceof ErrorBag) return $this->showError($r->getError());
		}
		$userDm->setMobile($mobile);
		$userDm->setAlipay($alipay);
		$result = $this->_editUser($userDm, PwUser::FETCH_MAIN + PwUser::FETCH_INFO);
		if ($result instanceof ErrorBag) {
			return $this->showError($result->getError());
		} else {
			$this->loginUser->info = array_merge($this->loginUser->info, $userDm->getData());
			return $this->showMessage('USER:user.edit.contact.success');
		}
	}
	
	/** 
	 * 密码验证
	 */
	public function editemailAction(Request $request) {
		$userInfo = app('user.PwUser')->getUserByUid($this->loginUser->uid, PwUser::FETCH_MAIN);
		->with($userInfo, 'userinfo');
	}
	
	/** 
	 * 密码验证
	 */
	public function doeditemailAction(Request $request) {
		$request->isPost() || return $this->showError('operate.fail');

		list($passwd, $email) = $request->get(array('passwd', 'email'), 'post');
		if (!$passwd || !$email) return $this->showError('USER:empty.error');
		Wind::import('SRV:user.srv.PwTryPwdBp');
		$tryPwdBp = new PwTryPwdBp();
		if (($result = $tryPwdBp->checkPassword($this->loginUser->uid, $passwd, $request->getClientIp())) instanceof ErrorBag) {
			list($error,) = $result->getError();
			if ($error == 'USER:login.error.pwd') {
				return $this->showError($result->getError());
			} else {
				Wind::import('SRC:service.user.srv.PwUserService');
				$srv = new PwUserService();
				$srv->logout();
				return redirect('u/login/run', array('backurl' => 'profile/index/run'));
			}
		}
		$userDm = new PwUserInfoDm($this->loginUser->uid);
		$r = PwUserValidator::isEmailValid($email, $this->loginUser->username);
		if ($r instanceof ErrorBag) return $this->showError($r->getError());
		$userDm->setEmail($email);
		$result = $this->_editUser($userDm, PwUser::FETCH_MAIN);
		if ($result instanceof ErrorBag) {
			return $this->showError($result->getError());
		} else {
			$this->loginUser->info = array_merge($this->loginUser->info, $userDm->getData());
			return $this->showMessage('USER:user.edit.contact.success', 'profile/index/contact?_tab=contact');
		}
	}
	
	/* (non-PHPdoc)
	 * @see PwBaseController::setDefaultTemplateName()
	 */
	protected function setDefaultTemplateName($handlerAdapter) {
		return view('profile_' . $handlerAdapter->getAction());
	}
	
	/**
	 * 编辑用户
	 *
	 * @param PwUserInfoDm $dm
	 * @param int $type
	 * @return boolean|ErrorBag
	 */
	private function _editUser($dm, $type = PwUser::FETCH_MAIN) {
		/* @var $userDs PwUser */
		$userDs = app('user.PwUser');
		$result = $userDs->editUser($dm, $type);
		if ($result instanceof ErrorBag) return $result;
		/*用户资料设置完成-基本资料-service钩子点:s_PwUserService_editUser*/
		SimpleHook::getInstance('profile_editUser')->runDo($dm);
		return true;
	}
	
	/**
	 * 设置地区显示
	 * 
	 * @return array
	 */
	private function _buildArea($areaid) {
		$default = array(array('areaid' => '', 'name' => ''), array('areaid' => '', 'name' => ''), array('areaid' => '', 'name' => ''));
		if (!$areaid) {
			return $default;
		}
		$rout = WindidApi::api('area')->getAreaRout($areaid);
		return Utility::mergeArray($default, $rout);
	}
}