<?php
Wind::import('ADMIN:library.AdminBaseController');

/**
 * 后台设置-站点设置-站点信息设置/全局参数设置
 *
 * @author Qiong Wu <papa0924@gmail.com> 2011-12-7
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.windframework.com
 * @version $Id: ConfigController.php 3935 2012-02-02 02:37:34Z gao.wanggao $
 * @package admin
 * @subpackage controller.config
 */
class ConfigController extends AdminBaseController {
	
	/**
	 * 站点设置-站点信息设置
	 *
	 * @return void
	 */
	public function run() {

		/* @var $userGroup PwUserGroups */
		$userGroup = app('usergroup.PwUserGroups');
		$groups = $userGroup->getAllGroups();
		$groupTypes = $userGroup->getTypeNames();
		$config = Core::C()->getValues('site');

		->with($config, 'config');
		->with($groups, 'groups');
		->with($groupTypes, 'groupTypes');

	}

	/**
	 * 配置增加表单处理器
	 *
	 * @return void
	 */
	public function dorunAction(Request $request) {
		$config = new PwConfigSet('site');
		$config->set('info.name', $request->get('infoName', 'post'))
			->set('info.url', $request->get('infoUrl', 'post'))
			->set('info.mail', $request->get('infoMail', 'post'))
			->set('info.icp', $request->get('infoIcp', 'post'))
			->set('info.logo', $request->get('infoLogo', 'post'))
			->set('statisticscode', $request->get('statisticscode', 'post'))
			->set('visit.state', $request->get('visitState', 'post'))
			->set('visit.group', $request->get('visitGroup', 'post'))
			->set('visit.gid', $request->get('visitGid', 'post'))
			->set('visit.ip', $request->get('visitIp', 'post'))
			->set('visit.member', $request->get('visitMember', 'post'))
			->set('visit.message', $request->get('visitMessage', 'post'))
			->flush();
		return $this->showMessage('ADMIN:success');
	}


	
	/**
	 * 站点设置
	 *
	 * @return void
	 */
	public function siteAction(Request $request) {
		$config = Core::C()->getValues('site');
		->with($config, 'config');
	
	}


		 
	/**
	 * 全局配置增加表单处理器
	 *
	 * @return void
	 */
	
	public function dositeAction(Request $request) {
		$configSet = new PwConfigSet('site');
		$configSet->set('time.cv', (int) $request->get('timeCv', 'post'))
			->set('time.timezone', $request->get('timeTimezone', 'post'))
			->set('refreshtime', (int) $request->get('refreshtime', 'post'))
			->set('onlinetime', (int) $request->get('onlinetime', 'post'))
			->set('debug', $request->get('debug', 'post'))
			->set('managereasons', $request->get('managereasons', 'post'))
//			->set('scorereasons', $request->get('scorereasons', 'post'))
			->set('cookie.path', $request->get('cookiePath'), 'post')
			->set('cookie.domain', $request->get('cookieDomain', 'post'))
			->set('cookie.pre', $request->get('cookiePre', 'pre'))
			->flush();
		app('domain.srv.PwDomainService')->refreshTplCache();
		
		/*
		$service = $this->_loadConfigService();
		$config = $service->getValues('site');
		if ($config['windid'] != 'client') {
			$windid = $this->_getWindid();
			$windid->setConfig('site', 'timezone', $request->get('timeTimezone', 'post'));
			$windid->setConfig('site', 'timecv', (int)$request->get('timeCv', 'post'));
		}
		*/
		return $this->showMessage('ADMIN:success');

	}
	
	protected function _getWindid() {
		return WindidApi::api('config');
	}
}