<?php
Wind::import('ADMIN:library.AdminBaseController');
/**
 * 后台菜单管理操作类
 * 
 * 后台菜单管理操作类<code>
 * 1. run 后台权限入口
 * </code>
 * @author Qiong Wu <papa0924@gmail.com> 2011-10-21
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.windframework.com
 * @version $Id: AuthController.php 28781 2013-05-23 09:33:37Z jieyin $
 * @package admin
 * @subpackage controller
 */
class AuthController extends AdminBaseController {

	private $perpage = 10;

	/**
	 * 菜单管理主入口
	 * 
	 * @return void
	 */
	public function run() {
		list($page) = $request->get(array('page'), 'get');
		/* @var $service AdminAuthService */
		$service = app('ADMIN:service.srv.AdminAuthService');
		list($count, $list, $page) = $service->fetchByPage($page, $this->perpage);
		
		->with($list, 'list');
		->with($page, 'page');
		->with($count, 'count');
		->with($this->perpage, 'per');
	}

	/**
	 * 删除后台用户操作
	 * 
	 * @return void
	 */
	public function delAction(Request $request) {
		/* @var $service AdminAuthService */
		$id = $request->get('id', 'post');
		!$id && return $this->showError('operate.fail');

		$service = app('ADMIN:service.srv.AdminAuthService');
		$result = $service->del($id);
		if ($result instanceof ErrorBag) return $this->showError($result->getError());
		return $this->showMessage('ADMIN:success');
	}

	/**
	 * 展示编辑用户操作界面
	 * 
	 * @return void
	 */
	public function editAction(Request $request) {
		$id = $request->get('id', 'get');
		if (!$id) return $this->showError('ADMIN:auth.edit.fail.id.illegal');
		$user = $this->_loadAuthService()->findById($id);
		if ($user instanceof ErrorBag) return $this->showError('ADMIN:auth.edit.fail.user.exist');
		/* @var $userService AdminUserService */
		$userService = app('ADMIN:service.srv.AdminUserService');
		$_user = $userService->getUserByUids($user['uid']);
		if ($_user) {
			$user['username'] = $_user['username'];
		}
		$roles = app('ADMIN:service.AdminRole')->findRoles();
		$_tmp = array();
		foreach ($roles as $role) {
			if (strpos(',' . $user['roles'] . ',', ',' . $role['name'] . ',') === false) continue;
			$_tmp[] = $role;
		}
		$user['roles'] = $_tmp;
		->with($user, 'user');
		->with($roles, 'roles');
	}

	/**
	 * 编辑用户
	 * 
	 * @return void
	 */
	public function doEditAction(Request $request) {
		list($id, $roles) = $request->get(array('id', 'userRoles'), 'post');
		/* @var $service AdminAuthService */
		$service = app('ADMIN:service.srv.AdminAuthService');
		$result = $service->edit($id, $roles);
		if ($result instanceof ErrorBag) return $this->showError($result->getError());
		return $this->showMessage('ADMIN:auth.edit.success');
	}

	/**
	 * 搜索用户操作
	 * 
	 * @return void
	 */
	public function addAction(Request $request) {
		/* @var $role AdminRole */
		$role = app('ADMIN:service.AdminRole');
		$roles = $role->findRoles();
		->with($roles, 'roles');
	}

	/**
	 * 添加用户权限
	 * 
	 * @return void
	 */
	public function doAddAction(Request $request) {
		list($username, $roles) = $request->get(array('username', 'userRoles'), 'post');
		/* @var $service AdminAuthService */
		$service = app('ADMIN:service.srv.AdminAuthService');
		$result = $service->add($username, $roles);
		if ($result instanceof ErrorBag) return $this->showError($result->getError());
		
		->with($result, 'data');
		return $this->showMessage('ADMIN:auth.add.success');
	}

	/**
	 * @return AdminAuth
	 */
	private function _loadAuthService() {
		return app('ADMIN:service.AdminAuth');
	}
}

?>
