<?php

/**
 * 系统默认全局filter
 *
 * @author Qiong Wu <papa0924@gmail.com> 2011-12-2
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.windframework.com
 * @version $Id: PwGlobalFilter.php 25328 2013-03-12 10:11:25Z jieyin $
 * @package src
 * @subpackage library.filter
 */
class PwGlobalFilter extends PwBaseFilter {
	
	/* (non-PHPdoc)
	 * @see WindHandlerInterceptor::preHandle()
	 */
	public function preHandle() {
		/* 模板变量设置 */

		$url = array();
		$var = Core::url();
		$url['base'] = $var->base;
		$url['res'] = $var->res;
		$url['css'] = $var->css;
		$url['images'] = $var->images;
		$url['js'] = $var->js;
		$url['attach'] = $var->attach;
		$url['themes'] = $var->themes;
		$url['extres'] = $var->extres;
		Core::setGlobal($url, 'url');

		$request = array(
			'm' => $this->router->getModule(),
			'c' => $this->router->getController(),
			'a' => $this->router->getAction(),
		);
		$request['mc'] = $request['m'] . '/' . $request['c'];
		$request['mca'] = $request['mc'] . '/' . $request['a'];
		Core::setGlobal($request, 'request');
		Core::setV('request', $request);

		$this->_setPreCache($request['m'], $request['mc'], $request['mca']);
		$loginUser = Core::getLoginUser();

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

		//门户管理模式 编译目录切换
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
		
		/*[设置给PwGlobalFilters需要的变量]*/
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
	 * 门户流程控制
	 */
	protected function runDesign() {
		$request = Core::V('request');
		$pageName = $unique = '';
		$pk = 0;
		if ($request['mca'] == 'bbs/read/run') return true;//帖子阅读页在ReadController里处理
		$sysPage = app('design.srv.router.PwDesignRouter')->get();
		if (!isset($sysPage[$request['mca']]))return false;
		list($pageName, $unique) = $sysPage[$request['mca']];
		$unique && $pk = $request->get($unique, 'get');
		if (!$pk) return false;
		Wind::import('SRV:design.bo.PwDesignPageBo');
    	$bo = new PwDesignPageBo();
    	$pageid = $bo->getPageId($request['mca'], $pageName, $pk);
		$pageid && $this->forward->getWindView()->compileDir = 'DATA:compile.design.'.$pageid;
		return true;
	}
	
	/**
	 * 首页绑定计划任务
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
	 * 在线服务  	
	 */
	protected function updateOnline() {
		$loginUser = Core::getLoginUser();
		$request = Core::V('request');
		if ($loginUser->uid > 0 && $request['mca'] == 'bbs/read/run') return false; //帖子阅读页在ReadController里处理
		if ($loginUser->uid > 0 && $request['m'] == 'space') return false; //空间在spaceBaseController里处理
		$online = app('online.srv.PwOnlineService');
		// $service->clearNotOnline(); // 由计划任务清理
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