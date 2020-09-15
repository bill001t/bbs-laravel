<?php
Wind::import('ADMIN:library.AdminBaseController');
/**
 * the last known user to change this file in the repository  <$LastChangedBy: jieyin $>
 * @author $Author: jieyin $ Foxsee@aliyun.com
 * @copyright ?2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: StorageController.php 28806 2013-05-24 08:06:26Z jieyin $ 
 * @package 
 */
class StorageController extends AdminBaseController { 
	
	public function beforeAction($handlerAdapter) {
		parent::beforeAction($handlerAdapter);
		$config = Core::C('site', 'windid');
		if ($config == 'client') {
			return $this->showError('WINDID:is.server.config');
		}
	}
	/**
	 * 附件存储方式设置列表页
	 */
	public function run() {
		Wind::import('WINDID:service.config.storage.WindidAttacmentService');
		$attService = new WindidAttacmentService('PwAttacmentService_getStorages');
		$storages = $attService->getStorages();
		$service = $this->_getConfigDs();
		$config = $service->getValues('attachment');
		$storageType = 'local';
		if (isset($config['storage.type']) && isset($storages[$config['storage.type']])) {
			$storageType = $config['storage.type'];
		}
		->with($config, 'config');
		->with($storages, 'storages');
		->with($storageType, 'storageType');
	}

	/**
	 * 附件存储方式设置列表页
	 */
	public function dostroageAction(Request $request) {
		$request->isPost() || return $this->showError('operate.fail');

		$att_storage = $request->get('att_storage', 'post');
		$avatarurl = $request->get('avatarurl', 'post');
		
		/*Wind::import('WINDID:service.config.storage.WindidAttacmentService');
		$attService = new WindidAttacmentService('PwAttacmentService_getStorages');
		$_r = $attService->setStoragesComponents($att_storage);*/
		
		Wind::import('WINDID:service.config.storage.WindidAttacmentService');
		$attService = new WindidAttacmentService('PwAttacmentService_getStorages');
		$storages = $attService->getStorages();
		
		Wind::import('WINDID:service.config.srv.WindidConfigSet');
		$config = new WindidConfigSet('attachment');
		$config->set('avatarurl', $avatarurl)->set('storage.type', $att_storage)->flush();
		$config = new WindidConfigSet('storage');
		foreach ($storages AS $key=>$storage) {
			$config->set($key, serialize($storage))->flush();
		}
		
		Wind::import('SRV:service.config.srv.PwConfigSet');
		$config = new PwConfigSet('site');
		$config->set('avatar.url', $avatarurl)->set('avatar.storage', $att_storage)->flush();
		
		return $this->showMessage('WINDID:success');
	}

	/**
	 * 后台设置-ftp设置
	 */
	public function ftpAction(Request $request) {
		$service = $this->_getConfigDs();
		$config = $service->getValues('attachment');
		->with($config, 'config');
	}

	/**
	 * 后台设置-ftp设置
	 */
	public function doftpAction(Request $request) {
		$request->isPost() || return $this->showError('operate.fail');

		Wind::import('WINDID:service.config.srv.WindidConfigSet');
		$config = new WindidConfigSet('attachment');
		$config->set('ftp.url', $request->get('ftpUrl', 'post'))
			->set('ftp.server', $request->get('ftpServer', 'post'))
			->set('ftp.port', $request->get('ftpPort', 'post'))
			->set('ftp.dir', $request->get('ftpDir', 'post'))
			->set('ftp.user', $request->get('ftpUser', 'post'))
			->set('ftp.pwd', $request->get('ftpPwd', 'post'))
			->set('ftp.timeout', abs(intval($request->get('ftpTimeout', 'post'))))
			->flush();
		return $this->showMessage('WINDID:success');
	}
	
	private function _getConfigDs() {
		return Windid::load('config.WindidConfig');
	}
}
?>