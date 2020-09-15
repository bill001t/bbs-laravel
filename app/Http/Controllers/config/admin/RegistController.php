<?php
Wind::import('ADMIN:library.AdminBaseController');

/**
 * 后台设置-注册登录设置
 *
 * @author Qiong Wu <papa0924@gmail.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: RegistController.php 4132 2012-02-11 05:35:07Z xiaoxia.xuxx $
 * @package 
 */
class RegistController extends AdminBaseController {
	
	/* (non-PHPdoc)
	 * @see WindController::run()
	 */
	public function run() {
		/* @var $userGroup PwUserGroups */
		$userGroup = app('usergroup.PwUserGroups');
		$groups = $userGroup->getAllGroups();
		$groupTypes = $userGroup->getTypeNames();
		
		Wind::import('SRV:credit.bo.PwCreditBo');
		/* @var $pwCreditBo PwCreditBo */
		$pwCreditBo = PwCreditBo::getInstance();

		$config = Core::C()->getValues('register');
		if (!$config['active.field']) $config['active.field'] = array();

		$wconfig = app(\App\Core\WindidBo::class)->config->C('reg');
		$config['security.username.min'] = $wconfig['security.username.min'];
		$config['security.username.max'] = $wconfig['security.username.max'];
		$config['security.password.min'] = $wconfig['security.password.min'];
		$config['security.password.max'] = $wconfig['security.password.max'];
		$config['security.password'] = $wconfig['security.password'];
		$config['security.ban.username'] = $wconfig['security.ban.username'];

		->with($config, 'config');
		->with($pwCreditBo->cType, 'credits');
		->with($groups, 'groups');
		->with($groupTypes, 'groupTypes');
	}

	/**
	 * 配置增加表单处理器
	 *
	 * @return void
	 */
	public function dorunAction(Request $request) {
		$request->isPost() || return $this->showError('operate.fail');

		$username_max = abs($request->get('securityUsernameMax', 'post'));
		$username_min = abs($request->get('securityUsernameMin', 'post'));
		$username_max = max(array($username_max, $username_min));
		$username_max > 15 && $username_max = 15;
		$username_min = min(array($username_max, $username_min));
		$username_min < 1 && $username_min = 1;
		$password_max = abs($request->get('securityPasswordMax', 'post'));
		$password_min = abs($request->get('securityPasswordMin', 'post'));
		$password_max = max(array($password_max, $password_min));
		$password_min = min(array($password_max, $password_min));
		$password_min < 1 && $password_min = 1;
		$password_security = $request->get('securityPassword', 'post');
		
		$ipTime = ceil($request->get('securityIp', 'post'));
		if ($ipTime < 0) $ipTime = 1;
		$config = new PwConfigSet('register');
		$config->set('type', $request->get('type', 'post'))
			->set('protocol', $request->get('protocol', 'post'))
			->set('active.field', $request->get('activeField', 'post'))
			->set('active.mail', $request->get('activeMail', 'post'))
			->set('active.mail.title', $request->get('activeTitle', 'post'))
			->set('active.mail.content', $request->get('activeContent', 'post'))
			->set('active.phone', $request->get('activePhone', 'post'))
			->set('active.check', $request->get('activeCheck', 'post'))
			->set('security.ban.username', $request->get('securityBanUsername', 'post'))
			->set('security.username.max', $username_max)
			->set('security.username.min', $username_min)
			->set('security.password', $password_security)
			->set('security.password.max', $password_max)
			->set('security.password.min', $password_min)
			->set('security.ip', $ipTime)
			->set('welcome.type', $request->get('welcomeType', 'post'))
			->set('welcome.title', $request->get('welcomeTitle', 'post'))
			->set('welcome.content', $request->get('welcomeContent', 'post'))
			->set('close.msg', $request->get('closeMsg', 'post'))
			->set('invite.expired', ceil($request->get('inviteExpired', 'post')))
			->set('invite.credit.type', $request->get('inviteCreditType', 'post'))
			->set('invite.reward.credit.num', $request->get('inviteRewardCreditNum', 'post'))
			->set('invite.reward.credit.type', $request->get('inviteRewardCredit', 'post'))
			->set('invite.pay.open', $request->get('invitePayState', 'post'))
			->set('invite.pay.money', $request->get('invitePayMoney', 'post'))
			->flush();
			
		//同步设置到Windid中
		$windid = $this->_getWindid();
		$windid->setConfig('reg', 'security.username.min', $username_min);
		$windid->setConfig('reg', 'security.username.max', $username_max);
		$windid->setConfig('reg', 'security.password.min', $password_min); 
		$windid->setConfig('reg', 'security.password.max', $password_max);
		$windid->setConfig('reg', 'security.password', $password_security);  
		$windid->setConfig('reg', 'security.ban.username', $request->get('securityBanUsername', 'post'));
		return $this->showMessage('ADMIN:success');
	}
	
	/**
	 * 站点设置
	 *
	 * @return void
	 */
	public function loginAction(Request $request) {
		/* @var $userGroup PwUserGroups */
		$userGroup = app('usergroup.PwUserGroups');
		$groups = $userGroup->getAllGroups();
		$groupTypes = $userGroup->getTypeNames();

		$config = Core::C()->getValues('login');
		if (!$config['question.groups']) $config['question.groups'] = array();
		->with($config, 'config');
		->with($groups, 'groups');
		->with($groupTypes, 'groupTypes');
	}
	
	/**
	 * 全局配置增加表单处理器
	 *
	 * @return void
	 */
	public function dologinAction(Request $request) {
		$request->isPost() || return $this->showError('operate.fail');

		$way = $request->get('ways', 'post');
		if (!$way) return $this->showError('config.login.type.require');
		$config = new PwConfigSet('login');
		$config->set('ways', $request->get('ways', 'post'))
			->set('trypwd', $request->get('trypwd', 'post'))
			->set('question.groups', $request->get('questionGroups', 'post'))
			->set('resetpwd.mail.title', $request->get('resetPwdMailTitle', 'post'))
			->set('resetpwd.mail.content', $request->get('resetPwdMailContent', 'post'))
			->flush();
		return $this->showMessage('operate.success');

	}
	
	/**
	 * 用户引导页面
	 */
	public function guideAction(Request $request) {
		/* @var $guideService PwUserRegisterGuideService */
		$guideService = app('APPS:u.service.PwUserRegisterGuideService');
		->with($guideService->getGuideList(), 'list');
	}
	

	/**
	 * 用户引导页面设置
	 */
	public function doguideAction(Request $request) {
		$config = $request->get('config', 'post');
		if (!$config) return $this->showError('ADMIN:fail');
		/* @var $guideService PwUserRegisterGuideService */
		$guideService = app('APPS:u.service.PwUserRegisterGuideService');
		$guideService->setConfig($config);
		return $this->showMessage('ADMIN:success', 'config/regist/guide');
	}
	
	protected function _getWindid() {
		return WindidApi::api('config');
	}
}