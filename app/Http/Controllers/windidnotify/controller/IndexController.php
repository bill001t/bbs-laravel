<?php
define('WINDID_IS_NOTIFY', 1);

Wind::import('APPS:windidnotify.service.PwWindidInform');
Wind::import('LIB:utility.PwWindidStd');
Wind::import('WINDID:service.base.WindidUtility');

/**
 * the last known user to change this file in the repository  <$LastChangedBy: gao.wanggao $>
 * @author $Author: gao.wanggao $ Foxsee@aliyun.com
 * @copyright ?2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: IndexController.php 29741 2013-06-28 07:54:24Z gao.wanggao $ 
 * @package 
 */
class IndexController extends Controller{
	
	public  function beforeAction($handlerAdapter) {	
		parent::beforeAction($handlerAdapter);
		$_windidkey = $request->get('windidkey', 'get');
		$_time = (int)$request->get('time', 'get');
		$_clentid = (int)$request->get('clientid', 'get');

		$windidConfig = Core::C('windid');
		define('WINDID_CONNECT', $windidConfig['connect']);
		define('WINDID_SERVER_URL', $windidConfig['serverUrl']);
		define('WINDID_CLIENT_ID', $windidConfig['clientId']);
		define('WINDID_CLIENT_KEY', $windidConfig['clientKey']);

		if (WindidUtility::appKey(WINDID_CLIENT_ID, $_time, WINDID_CLIENT_KEY, $request->getGet(null), $request->getPost()) != $_windidkey) return $this->showError('fail');
		$time = Tool::getTime();
		if ($time - $_time > 120) return $this->showError('timeout');
	}
	
	public function run() {
		$operation = (int)$request->get('operation', 'get');
		list($method, $args) = $this->getMethod($operation);
		if (!$method) return $this->showError('fail');
		$srv = new PwWindidInform();
		if(!method_exists($srv, $method)) return $this->showMessage('success'); //不指定的方法，默认返回成功状态
		$args = $request->get($args);
		$result = call_user_func_array(array($srv,$method), $args);
		if ($result == true) return $this->showMessage('success');
		return $this->showError('fail');
	}
	
	protected function getMethod($operation) {
		$config = include Wind::getRealPath('WINDID:service.base.WindidNotifyConf.php', true);
		$method = isset($config[$operation]['method']) ? $config[$operation]['method'] : '';
		$args = isset($config[$operation]['args']) ? $config[$operation]['args'] : array();
		return array($method, $args);
	}
	
	protected function showError($message = '', $referer = '', $refresh = false) {
		Tool::echoStr($message);
		exit();
	}

	protected function showMessage($message = '', $referer = '', $refresh = false) {
		Tool::echoStr($message);
		exit();
	}
}

?>
