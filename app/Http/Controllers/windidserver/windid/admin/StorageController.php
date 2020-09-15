<?php
Wind::import('APPS:windid.admin.WindidBaseController');
/**
 * the last known user to change this file in the repository  <$LastChangedBy: jieyin $>
 * @author $Author: jieyin $ Foxsee@aliyun.com
 * @copyright ?2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: StorageController.php 24648 2013-02-04 02:31:11Z jieyin $ 
 * @package 
 */
class StorageController extends WindidBaseController {

	/**
	 * 附件存储方式设置列表页
	 */
	public function run() {
		$attService = app('LIB:storage.PwStorage');
		$storages = $attService->getStorages();
		$config = Core::C()->getValues('attachment');
		$storageType = 'local';
		if (isset($config['storage.type']) && isset($storages[$config['storage.type']])) {
			$storageType = $config['storage.type'];
		}
		$c = Core::C()->getValues('site');
		$config['avatarUrl'] = $c['avatarUrl'];

		->with($config, 'config');
		->with($storages, 'storages');
		->with($storageType, 'storageType');
	}

	/**
	 * 附件存储方式设置列表页
	 */
	public function dostroageAction(Request $request) {
		$att_storage = $request->get('att_storage', 'post');
		$avatarurl = $request->get('avatarurl', 'post');
		
		$attService = app('LIB:storage.PwStorage');
		$_r = $attService->setStoragesComponents($att_storage);
		if ($_r !== true) {
			return $this->showError($_r->getError());
		}
		$config = new PwConfigSet('attachment');
		$config->set('storage.type', $att_storage)->flush();
		
		$components = Core::C()->get('components')->toArray();
		Wind::getApp()->getFactory()->loadClassDefinitions($components);
		Core::C()->setConfig('site', 'avatarUrl', substr(Tool::getPath('1.gpg'), 0, -6));

		app('WSRV:notify.srv.WindidNotifyService')->send('alterAvatarUrl', array());

		return $this->showMessage('WINDID:success');
	}
}
?>