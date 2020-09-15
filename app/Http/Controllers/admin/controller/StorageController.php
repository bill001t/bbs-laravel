<?php

Wind::import('ADMIN:library.AdminBaseController');

/**
 * @author Qiong Wu <papa0924@gmail.com> 2011-12-15
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.windframework.com
 * @version $Id: StorageController.php 28785 2013-05-23 09:54:16Z jieyin $
 * @package admin
 * @subpackage controller.config
 */
class StorageController extends AdminBaseController {
	
	/* (non-PHPdoc)
	 * @see WindController::run()
	 */
	public function run() {
		
	}

	/**
	 * 后台设置-ftp设置
	 */
	public function ftpAction(Request $request) {
		$config = Core::C()->getValues('attachment');
		->with($config, 'config');
	}

	/**
	 * 后台设置-ftp设置
	 */
	public function doftpAction(Request $request) {
		$request->isPost() || return $this->showError('operate.fail');

		$config = new PwConfigSet('attachment');
		$config->set('ftp.url', $request->get('ftpUrl', 'post'))
			->set('ftp.server', $request->get('ftpServer', 'post'))
			->set('ftp.port', $request->get('ftpPort', 'post'))
			->set('ftp.dir', $request->get('ftpDir', 'post'))
			->set('ftp.user', $request->get('ftpUser', 'post'))
			->set('ftp.pwd', $request->get('ftpPwd', 'post'))
			->set('ftp.timeout', abs(intval($request->get('ftpTimeout', 'post'))))
			->flush();
		return $this->showMessage('ADMIN:success');
	}
}
?>