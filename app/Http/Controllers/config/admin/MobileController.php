<?php
Wind::import('ADMIN:library.AdminBaseController');

/**
 * 后台设置 - 手机验证
 *
 * @author jinlong.panjl <jinlong.panjl@aliyun-inc.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id$
 * @package wind
 */
class MobileController extends AdminBaseController {

	/* (non-PHPdoc)
	 * @see WindController::run()
	 */
	public function setAction(Request $request) {
		$registerConfig = Core::C()->getValues('register');
		$loginConfig = Core::C()->getValues('login');
		$mobileConfig = Core::C()->getValues('mobile');
		if (!$mobileConfig['plat.type']) {
			return $this->showError('USER:mobile.plat.choose.error', 'config/mobile/run', true);
		}
		$mobileService = app('SRV:mobile.srv.PwMobileService');
		$restMessage = $mobileService->getRestMobileMessage();
		if ($restMessage instanceof ErrorBag) {
			return $this->showError($restMessage->getError());
		}
		$appMobileUrl = $mobileService->platUrl;
		->with($appMobileUrl, 'appMobileUrl');
		->with($restMessage, 'restMessage');
		->with($registerConfig, 'registerConfig');
		->with($loginConfig, 'loginConfig');
	}

	/**
	 * 后台设置-手机设置
	 */
	public function dosetAction(Request $request) {
		$request->isPost() || return $this->showError('operate.fail');

		$config = new PwConfigSet('register');
		$config->set('active.phone', $request->get('activePhone', 'post'))
				->set('mobile.message.content', $request->get('mobileMessageContent', 'post'))
				->flush();
		$loginConfig = Core::C()->getValues('login');
		$ways = $request->get('ways', 'post');
		$loginConfigWays = array_flip($loginConfig['ways']);
		unset($loginConfigWays[4]);
		$loginConfigWays = array_flip($loginConfigWays);
		$ways && $loginConfigWays[] = 4;
		$config = new PwConfigSet('login');
		$config->set('ways', $loginConfigWays);
		$config->set('mobieFindPasswd', $request->get('mobieFindPasswd', 'post'))
			->flush();
		
		return $this->showMessage('ADMIN:success');
	}

	/**
	 * 后台设置-短信平台
	 */
	public function run() {
		Wind::import('SRV:mobile.srv.PwMobileConfigService');
		$service = new PwMobileConfigService('PwMobileService_getPlats');
		$plats = $service->getPlats();
		
		$config = Core::C()->getValues('mobile');
		$platType = 'aliyun';
		if (isset($config['plat.type']) && isset($plats[$config['plat.type']])) {
			$paltType = $config['plat.type'];
		}
		->with($plats, 'plats');
		->with($paltType, 'paltType');
	}
	
	/**
	 * 方式设置列表页
	 */
	public function dorunAction(Request $request) {
		$mobile_plat = $request->get('mobile_plat', 'post');
		if (!$mobile_plat) return $this->showError('USER:mobile.plat.choose.empty');
		/* @var $attService PwAttacmentService */
		Wind::import('SRV:mobile.srv.PwMobileConfigService');
		$service = new PwMobileConfigService('PwMobileService_getPlats');
		$_r = $service->setPlatComponents($mobile_plat);
		
		if ($_r === true) return $this->showMessage('ADMIN:success');
		/* @var $_r ErrorBag  */
		return $this->showError($_r->getError());
	}
}