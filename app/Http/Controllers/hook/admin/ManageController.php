<?php
Wind::import('ADMIN:library.AdminBaseController');
Wind::import('SRV:hook.dm.PwHookDm');
/**
 * hook管理
 *
 * @author Shi Long <long.shi@alibaba-inc.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.windframework.com
 * @version $Id: ManageController.php 28812 2013-05-24 09:08:16Z jieyin $
 * @package hook
 */
class ManageController extends AdminBaseController {
	private $perpage = 30;
	private $sep = "\r\n";
	/**
	 * hook列表
	 *
	 * @see WindController::run()
	 */
	public function run() {
		$count = $this->_hookDs()->count();
		$page = intval($request->get('page'));
		$page < 1 && $page = 1;
		list($start, $num) = Tool::page2limit($page, $this->perpage);
		$hooks = $this->_hookDs()->fetchList($num, $start, 'name');
		->with(
			array(
				'page' => $page, 
				'perpage' => $this->perpage, 
				'count' => $count, 
				'hooks' => $hooks));
	}

	/**
	 * 展示添加hook页面
	 */
	public function addAction(Request $request) {
		/* @var $appDs PwApplication */
		$appDs = app('APPCENTER:service.PwApplication');
		$apps = $appDs->fetchByPage(0);
		->with($apps, 'apps');
	}

	/**
	 * 添加hook
	 */
	public function doAddAction(Request $request) {
		$request->isPost() || return $this->showError('operate.fail');

		list($name, $app, $dec, $param, $interface) = $request->get(array('name', 'app', 'dec', 'param', 'interface'), 'post');
		list($appId, $appName) = explode('|', $app);
		$r = $this->_hookDs()->fetchByName($name);
		if ($r) return $this->showError(array('HOOK:hook.exit', array('{{error}}' => $name)));
		$dm = new PwHookDm();
		$dm->setAppId($appId);
		$dm->setAppName($appName);
		$dm->setDocument(implode($this->sep, array($dec, $param, $interface)));
		$dm->setName($name);
		$dm->setCreatedTime(Tool::getTime());
		$r = $this->_hookDs()->add($dm);
		if ($r instanceof ErrorBag) {
			return $this->showError($r->getError());
		}
		return $this->showMessage('success');
	}

	/**
	 * hook编辑展示
	 */
	public function editAction(Request $request) {
		$name = $request->get('name');
		$hook = $this->_hookDs()->fetchByName($name);
		/* @var $appDs PwApplication */
		$appDs = app('APPCENTER:service.PwApplication');
		$apps = $appDs->fetchByPage(0);
		->with($apps, 'apps');
		->with($hook, 'hook');
		
		list($dec, $param, $interface) = explode($this->sep, $hook['document']);
		->with(array('dec' => $dec, 'param' => $param, 'interface' => $interface));
	}

	/**
	 * hook编辑
	 */
	public function doEditAction(Request $request) {
		$request->isPost() || return $this->showError('operate.fail');

		list($name, $app, $dec, $param, $interface) = $request->get(array('name', 'app', 'dec', 'param', 'interface'), 'post');
		list($appId, $appName) = explode('|', $app);
		$dm = new PwHookDm();
		$dm->setAppId($appId);
		$dm->setAppName($appName);
		$dm->setDocument(implode($this->sep, array($dec, $param, $interface)));
		$dm->setName($name);
		$dm->setModifiedTime(Tool::getTime());
		$r = $this->_hookDs()->update($dm);
		if ($r instanceof ErrorBag) {
			return $this->showError($r->getError());
		}
		return $this->showMessage('success');
	}

	/**
	 * 删除hook
	 */
	public function delAction(Request $request) {
		$request->isPost() || return $this->showError('operate.fail');

		$name = $request->get('name');
		$r = $this->_hookDs()->delByName($name);
		$r && $r = $this->_injectDs()->delByHookName($name);
		return $this->showMessage('success');
	}

	/**
	 * 搜索页
	 */
	public function searchAction(Request $request) {
		list($name, $app_name) = $request->get(array('name', 'app_name'));
		Wind::import('SRV:hook.dm.PwHookSo');
		$so = new PwHookSo();
		$so->setAppName($app_name)->setName($name);
		$page = intval($request->get('page'));
		$page < 1 && $page = 1;
		list($start, $num) = Tool::page2limit($page, $this->perpage);
		$hooks = $this->_hookDs()->searchHook($so, $num, $start);
		->with(
			array(
				'page' => $page, 
				'perpage' => $this->perpage, 
				'name' => $name, 
				'app_name' => $app_name, 
				'hooks' => $hooks, 
				'search' => 1));
		return view('manage_run');
	}

	/**
	 * hook详细页
	 */
	public function detailAction(Request $request) {
		$name = $request->get('name');
		$hook = $this->_hookDs()->fetchByName($name);
		$injectors = $this->_injectDs()->findByHookName($name);
		->with(array('hook' => $hook, 'injectors' => $injectors));
		
		list($dec, $param, $interface) = explode($this->sep, $hook['document']);
		->with(array('dec' => $dec, 'param' => $param, 'interface' => $interface));
	}
	
	/**
	 *
	 * @return PwHooks
	 */
	private function _hookDs() {
		return app('hook.PwHooks');
	}

	/**
	 *
	 * @return PwHookInject
	 */
	private function _injectDs() {
		return app('hook.PwHookInject');
	}
}

?>