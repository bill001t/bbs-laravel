<?php
defined('WEKIT_VERSION') || exit('Forbidden');
Wind::import('APPS:windid.admin.WindidBaseController');

/**
 * the last known user to change this file in the repository  <$LastChangedBy: gao.wanggao $>
 * @author $Author: gao.wanggao $ Foxsee@aliyun.com
 * @copyright ?2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: ClientController.php 29741 2013-06-28 07:54:24Z gao.wanggao $ 
 * @package 
 */
class ClientController extends WindidBaseController { 
	
	public function run() {
		$list = $this->_getAppDs()->getList();
		$data = $urls = array();
		$time = Tool::getTime();
		->with($list, 'list');
	}
	
	public function clientTestAction(Request $request) {
		$clientid = $request->get('clientid');
		$client = $this->_getAppDs()->getApp($clientid);
		if (!$client) return $this->showError('WINDID:fail');
		$time = Tool::getTime();
		$array = array(
			'windidkey'=>WindidUtility::appKey($client['id'], $time, $client['secretkey'], array('operation'=>999), array()),
			'operation'=>999,
			'clientid'=>$client['id'],
			'time'=>$time
		);
		$post = array('testdata'=>1);
		$url = WindidUtility::buildClientUrl($client['siteurl'], $client['apifile']) . http_build_query($array);
		$result = WindidUtility::buildRequest($url, $post);
		if ($result === 'success')return $this->showMessage('WINDID:success');
		return $this->showError('WINDID:fail');
	}
	
	public function addAction(Request $request) {
		$rand = Utility::generateRandStr(10);
		->with(md5($rand), 'rand');
		->with('windid.php' , 'apifile');
	}
	
	public function doaddAction(Request $request) {
		$apifile = $request->get('apifile', 'post');
		if (!$apifile) $apifile = 'windid.php';
		Wind::import('WSRV:app.dm.WindidAppDm');
		$dm = new WindidAppDm();
		$dm->setApiFile($apifile)
			->setIsNotify($request->get('isnotify', 'post'))
			->setIsSyn($request->get('issyn', 'post'))
			->setAppName($request->get('appname', 'post'))
			->setSecretkey($request->get('appkey', 'post'))
			->setAppUrl($request->get('appurl', 'post'))
			->setAppIp($request->get('appip', 'post'))
			->setCharset($request->get('charset', 'post'));
		$result = $this->_getAppDs()->addApp($dm);
		if ($result instanceof WindidError) return $this->showError('WINDID:fail');
		return $this->showMessage('WINDID:success');
	}
	
	public function editAction(Request $request) {
		$app = $this->_getAppDs()->getApp(intval($request->get('id', 'get')));
		if (!$app) return $this->showMessage('WINDID:fail');
		->with($app, 'app');
	}
	
	public function doeditAction(Request $request) {
		Wind::import('WSRV:app.dm.WindidAppDm');
		$dm = new WindidAppDm(intval($request->get('id', 'post')));
		$dm->setApiFile($request->get('apifile', 'post'))
			->setIsNotify($request->get('isnotify', 'post'))
			->setIsSyn($request->get('issyn', 'post'))
			->setAppName($request->get('appname', 'post'))
			->setSecretkey($request->get('appkey', 'post'))
			->setAppUrl($request->get('appurl', 'post'))
			->setAppIp($request->get('appip', 'post'))
			->setCharset($request->get('charset', 'post'));
		$result = $this->_getAppDs()->editApp($dm);
		if ($result instanceof WindidError) return $this->showError('ADMIN:fail');
		return $this->showMessage('WINDID:success');
	}
	
	public function deleteAction(Request $request) {
		$result = $this->_getAppDs()->delApp(intval($request->get('id', 'get')));
		if ($result instanceof WindidError) return $this->showError('WINDID:fail');
		return $this->showMessage('WINDID:success');
	}
	
	private function _getAppDs() {
		return app('WSRV:app.WindidApp');
	}
}

?>
