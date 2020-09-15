<?php

namespace App\Core\base;

class GlobalFilter
{
	public function preHandle($request) {

		//$this->_setPreCache($request['m'], $request['mc'], $request['mca']);
		$loginUser =Auth::user();

		$config = Core::C('site');
		if ($config['visit.state'] > 0) {
			$service = app('site.srv.PwSiteStatusService');
			$resource = $service->siteStatus($loginUser, $config);
			if ($resource instanceof ErrorBag) {
				if (!($config['visit.state'] == 1 && $request['mc'] == 'u/login')) {
					return $this->showError($resource->getError());
				}
			}
		}
		if (!in_array($request['mc'], array('u/login', 'u/register', 'u/findPwd')) && !$loginUser->getPermission('allow_visit')) {
			if ($loginUser->isExists()) {
				return $this->showError(array('permission.visit.allow', array('{grouptitle}' => $loginUser->getGroupInfo('name'))));
			} else {
				return redirect('u/login/run'));
			}
		}
		if ($config['refreshtime'] > 0 && Wind::getApp()->getRequest()->isGet() && !Wind::getApp()->getRequest()->getIsAjaxRequest()) {
			if (Core::V('lastvist')->lastRequestUri == Core::V('lastvist')->requestUri && (Core::V('lastvist')->lastvisit + $config['refreshtime']) > Tool::getTime()) {
				return $this->showError('SITE:refresh.fast');
			}
		}
		$this->_setPreHook($request['m'], $request['mc'], $request['mca']);

		$debug = $config['debug'] || !$config['css.compress'];
		Core::setGlobal(array('debug' => $debug ? '/dev' : '/build'), 'theme');
	}
	
	/* (non-PHPdoc)
	 * @see WindHandlerInterceptor::postHandle()
	 */
	public function postHandle() {
		$this->runDesign();
		$this->updateOnline();
		->with($this->runCron(), 'runCron');

		//�Ż�����ģʽ ����Ŀ¼�л�
		if ($request->getPost('design')) {
			$loginUser = Core::getLoginUser();
			$designPermission = $loginUser->getPermission('design_allow_manage.push');
			if ($designPermission > 0) {
				$dir = Wind::getRealDir('DATA:design.template');
				if (is_dir($dir)) WindFolder::rm($dir, true);
				$this->forward->getWindView()->compileDir = 'DATA:design.template';
			}
		}
		
		// SEO settings
		Core::setGlobal(NEXT_VERSION . ' ' . NEXT_RELEASE, 'version');
		$seo = Core::V('seo');
		Core::setGlobal($seo ? $seo->getData() : array('title' => Core::C('site', 'info.name')), 'seo');
		
		->with($request->getIsAjaxRequest() ? '1' : '0', '_ajax_');
		
		/*[���ø�PwGlobalFilters��Ҫ�ı���]*/
		$_var = array(
			'current' => $this->forward->getWindView()->templateName,
			'a' => $this->router->getAction(),
			'c' => $this->router->getController(),
			'm' => $this->router->getModule());
		$this->getResponse()->setData($_var, '_aCloud_');
	}

	protected function _setPreCache($m, $mc, $mca) {
		$precache = Core::V('precache');
		if (isset($precache[$m])) Core::cache()->preset($precache[$m]);
		if (isset($precache[$mc])) Core::cache()->preset($precache[$mc]);
		if (isset($precache[$mca])) Core::cache()->preset($precache[$mca]);
	}

	protected function _setPreHook($m, $mc, $mca) {
		$prehook = Core::V('prehook');
		PwHook::preset($prehook['ALL']);
		PwHook::preset($prehook[Core::getLoginUser()->isExists() ? 'LOGIN' : 'UNLOGIN']);
		if (isset($prehook[$m])) PwHook::preset($prehook[$m]);
		if (isset($prehook[$mc])) PwHook::preset($prehook[$mc]);
		if (isset($prehook[$mca])) PwHook::preset($prehook[$mca]);
	}

	/**
	 * ��ҳ�󶨼ƻ�����
	 *
	 * @return string Ambigous string>
	 */
	protected function runCron() {
		if (!$homeRouter = Core::C('site', 'homeRouter')) return '';
		$ishome = false;
		$request = Core::V('request');
		$httpRequest = $request;
		if ($request['mca'] == $homeRouter['m'] . '/' . $homeRouter['c'] . '/' . $homeRouter['a']) {
			$ishome = true;
		}
		unset($homeRouter['m'], $homeRouter['c'], $homeRouter['a']);
		foreach ($homeRouter as $k => $v) {
			if (!$k) continue;
			if ($httpRequest->getAttribute($k) != $v) $ishome = false;
		}
		if (!$ishome) return '';
		$time = Tool::getTime();
		$cron = app('cron.PwCron')->getFirstCron();
		if (!$cron || $cron['next_time'] > $time) return '';
		return 'cron/index/run/';
	}

	/**
	 * ���߷���  	
	 */
	protected function updateOnline() {
		$loginUser = Core::getLoginUser();
		$request = Core::V('request');
		if ($loginUser->uid > 0 && $request['mca'] == 'bbs/read/run') return false; //�����Ķ�ҳ��ReadController�ﴦ��
		if ($loginUser->uid > 0 && $request['m'] == 'space') return false; //�ռ���spaceBaseController�ﴦ��
		$online = app('online.srv.PwOnlineService');
		// $service->clearNotOnline(); // �ɼƻ���������
		if ($loginUser->uid > 0 && $request['mca'] == 'bbs/thread/run') {
			$createdTime = $online->forumOnline($request->get('fid'));
		} else {
			$clientIp = $loginUser->ip;
			$createdTime = $online->visitOnline($clientIp);
		}
		if (!$createdTime) return false;
		$dm = app('online.dm.PwOnlineDm');
		$time = Tool::getTime();
		if ($loginUser->uid > 0) {
			$dm->setUid($loginUser->uid)->setUsername($loginUser->username)->setModifytime($time)->setCreatedtime($createdTime)->setGid($loginUser->gid)->setFid($request->get('fid', 'get'))->setRequest($request['mca']);
			app('online.PwUserOnline')->replaceInfo($dm);
		} else {
			$dm->setIp($clientIp)->setCreatedtime($createdTime)->setModifytime($time)->setFid($request->get('fid', 'get'))->setTid($request->get('tid', 'get'))->setRequest($request['mca']);
			app('online.PwGuestOnline')->replaceInfo($dm);
		}
	}
}
?>