<?php
Wind::import('ADMIN:library.AdminBaseController');
/**
 * 后台安全 - ip限制
 *
 * @author Shi Long <long.shi@alibaba-inc.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.windframework.com
 * @version $Id: SafeController.php 28784 2013-05-23 09:53:55Z jieyin $
 * @package admin.controller
 */
class SafeController extends AdminBaseController {
	
	/*
	 * (non-PHPdoc) @see WindController::run()
	 */
	public function run() {
		$ips = $this->_loadSafeService()->getAllowIps();
		$ips = implode(',', $ips);
		->with($ips, 'ips');
		->with(Wind::getComponent('request')->getClientIp(), 'clientIp');
	}

	/**
	 * 保存设置
	 */
	public function addAction(Request $request) {
		$request->isPost() || return $this->showError('operate.fail');

		$ips = $request->get('ips', 'post');
		$r = $this->_loadSafeService()->setAllowIps($ips);
		if ($r instanceof ErrorBag) return $this->showError($r->getError());
		return $this->showMessage('success');
	}

	/**
	 * @return AdminSafeService
	 */
	private function _loadSafeService() {
		return app('ADMIN:service.srv.AdminSafeService');
	}
}

?>