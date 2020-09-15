<?php
Wind::import('ADMIN:library.AdminBaseController');
Wind::import('SRV:hook.dm.PwHookInjectDm');
/**
 * inject
 *
 * @author Shi Long <long.shi@alibaba-inc.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.windframework.com
 * @version $Id: InjectController.php 28812 2013-05-24 09:08:16Z jieyin $
 * @package hook.admin
 */
class InjectController extends AdminBaseController {

	/**
	 * 添加inject展示页
	 */
	public function addAction(Request $request) {
		$hook_name = $request->get('hook_name');
		$hooks = $this->_hookDs()->fetchList(0);
		->with($hook_name, 'hook_name');
		->with($hooks, 'hooks');
	}

	/**
	 * 添加inject
	 */
	public function doAddAction(Request $request) {
		$request->isPost() || return $this->showError('operate.fail');

		list($alias, $hook_name, $class, $method, $loadway, $expression, $description) = $request->get(
			array('alias', 'hook_name', 'class', 'method', 'loadway', 'expression', 'description'));
		$dm = new PwHookInjectDm();
		$dm->setAlias($alias)->setHookName($hook_name)->setClass($class)->setMethod($method)->setLoadWay(
			$loadway)->setExpression($expression)->setDescription($description);
		$r = $this->_injectDs()->add($dm);
		if ($r instanceof ErrorBag) {
			return $this->showError($r->getError());
		}
		return $this->showMessage('success');
	}

	/**
	 * 编辑inject展示
	 */
	public function editAction(Request $request) {
		$id = $request->get('id');
		$inject = $this->_injectDs()->find($id);
		$hooks = $this->_hookDs()->fetchList(0);
		->with($hooks, 'hooks');
		->with($inject, 'inject');
	}

	/**
	 * 编辑inject
	 */
	public function doEditAction(Request $request) {
		$request->isPost() || return $this->showError('operate.fail');

		list($id, $alias, $hook_name, $class, $method, $loadway, $expression, $description) = $request->get(
			array(
				'id', 
				'alias', 
				'hook_name', 
				'class', 
				'method', 
				'loadway', 
				'expression', 
				'description'));
		$dm = new PwHookInjectDm();
		$dm->setId($id)->setAlias($alias)->setHookName($hook_name)->setClass($class)->setMethod(
			$method)->setLoadWay($loadway)->setExpression($expression)->setDescription($description);
		$r = $this->_injectDs()->update($dm);
		if ($r instanceof ErrorBag) {
			return $this->showError($r->getError());
		}
		return $this->showMessage('success');
	}

	/**
	 * 删除injector
	 */
	public function delAction(Request $request) {
		$request->isPost() || return $this->showError('operate.fail');

		$id = $request->get('id');
		$this->_injectDs()->del($id);
		return $this->showMessage('success');
	}

	/**
	 * injector详细页
	 */
	public function detailAction(Request $request) {
		$request->isPost() || return $this->showError('operate.fail');

		$id = $request->get('id');
		$inject = $this->_injectDs()->find($id);
		->with($inject, 'inject');
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