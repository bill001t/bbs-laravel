<?php
Wind::import('ADMIN:library.AdminBaseController');
Wind::import('SRV:user.dm.PwUserInfoDm');
/**
 * 后台创始人管理相关操作类
 * 
 * 创始人管理相关操作<code>
 * 1. run 创始人管理首页
 * 2. add 添加创始人
 * 3. del 删除创始人
 * </code>
 * @author Qiong Wu <papa0924@gmail.com> 2011-11-10
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.windframework.com
 * @version $Id: FounderController.php 28782 2013-05-23 09:37:11Z jieyin $
 * @package admin
 * @subpackage library
 */
class FounderController extends AdminBaseController {
	
	/* (non-PHPdoc)
	 * @see WindController::run()
	 */
	public function run() {
		$founder = $this->loadFounderService()->getFounders();
		$isWriteable = $this->loadFounderService()->isWriteable();
		->with($isWriteable, 'is_writeable');
		->with(array_keys($founder), 'list');
	}

	/**
	 * 添加创始人
	 */
	public function addAction(Request $request) {
		$username = $request->get('username', 'post');
		if ($this->loadFounderService()->isFounder($username)) return $this->showError(
			'ADMIN:founder.add.fail.username.duplicate');
		$args = array('username' => $username);
		return $this->showMessage('success', 'founder/show?' . WindUrlHelper::argsToUrl($args));
	}

	/**
	 * 展示创始人添加页
	 */
	public function showAction(Request $request) {
		$username = rawurldecode($request->get('username', 'get'));
		$user = $this->loadAdminUserService()->verifyUserByUsername($username);
		->with($username, 'username');
		->with(isset($user['email']) ? $user['email'] : '', 'email');
		->with(isset($user['uid']) ? $user['uid'] : 0, 'uid');
		
		return view('founder_add');
	}

	/**
	 * 添加创始人
	 */
	public function doAddAction(Request $request) {
		list($username, $password, $email) = $request->get(array('username', 'password', 'email'),
			'post');
		$r = $this->loadFounderService()->add($username, $password, $email);
		if ($r instanceof ErrorBag) return $this->showError($r->getError());
		return $this->showMessage('success', 'founder/run');
	}

	/**
	 * 编辑创始人
	 */
	public function editAction(Request $request) {
		$username = $request->get('username', 'get');
		if (!$this->loadFounderService()->isFounder($username)) return $this->showError(
			'ADMIN:founder.edit.fail');
		$user = $this->loadAdminUserService()->verifyUserByUsername($username);
		
		->with($username, 'username');
		->with(isset($user['email']) ? $user['email'] : '', 'email');
	}

	/**
	 * 修改创始人
	 */
	public function doEditAction(Request $request) {
		list($username, $password, $email) = $request->get(array('username', 'password', 'email'),
			'post');
		$r = $this->loadFounderService()->edit($username, $password, $email);
		if ($r instanceof ErrorBag) return $this->showError($r->getError());
		return $this->showMessage('success', 'founder/run');
	}

	/**
	 * 删除创始人
	 */
	public function delAction(Request $request) {
		$username = $request->get('username', 'post');
		!$username && return $this->showError('operate.fail');

		if ($this->loginUser->username == $username) return $this->showError('ADMIN:founder.del.fail.self');
		$result = $this->loadFounderService()->del($username);
		if ($result instanceof ErrorBag) return $this->showError($result->getError());
		return $this->showMessage('success');
	}

	/**
	 * @return AdminUserService
	 */
	private function loadAdminUserService() {
		return app('ADMIN:service.srv.AdminUserService');
	}

	/**
	 * @return AdminFounderService
	 */
	private function loadFounderService() {
		return app('ADMIN:service.srv.AdminFounderService');
	}
}
?>