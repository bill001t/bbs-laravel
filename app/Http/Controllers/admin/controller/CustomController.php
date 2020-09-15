<?php
Wind::import('ADMIN:library.AdminBaseController');
/**
 * 常用菜单定制
 *
 * @author Shi Long <long.shi@alibaba-inc.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.windframework.com
 * @version $Id: CustomController.php 24202 2013-01-23 02:18:05Z jieyin $
 * @package admin.controller
 */
class CustomController extends AdminBaseController {
	
	/* (non-PHPdoc)
	 * @see WindController::run()
	 */
	public function run() {
		$menuService = app('ADMIN:service.srv.AdminMenuService');
		$userService = app('ADMIN:service.srv.AdminUserService');
		$myMenus = $userService->getAuths($this->loginUser);
		$menuTables = $menuService->getMenuTable();
		if ($myMenus !== '-1') {
			foreach ($menuTables as $key => $value)
				if (isset($value['url']) && !in_array($key, (array) $myMenus)) unset(
					$menuTables[$key]);
		}
		$menus = AdminMenuHelper::resetMenuStruts($menuTables);
		foreach ($menus as $key => $value) {
			if (isset($value['items']) && empty($value['items'])) {
				unset($menus[$key]);
			}
		}
		->with($menus, 'menus');
		$myMenu = $this->_loadCustomDs()->findByUsername($this->loginUser->username);
		->with($myMenu ? explode(',', $myMenu['custom']) : array(), 'myMenu');
	}
	
	/**
	 * 保存设置
	 *
	 */
	public function doRunAction(Request $request) {
		$customs = $request->get('customs', 'post');
		$customs || $customs = array();
		if (count($customs) > 15) return $this->showError('ADMIN:custom.size');
		$this->_loadCustomDs()->replace($this->loginUser->username, implode(',', $customs));
		return $this->showMessage('success');
	}
	
	/**
	 * @return AdminCustom
	 */
	private function _loadCustomDs() {
		return app('ADMIN:service.AdminCustom');
	}
}

?>