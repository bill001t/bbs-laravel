<?php

/**
 * the last known user to change this file in the repository  <$LastChangedBy: gao.wanggao $>
 * @author $Author: gao.wanggao $ Foxsee@aliyun.com
 * @copyright ?2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: OpenBaseController.php 28968 2013-05-31 12:05:48Z gao.wanggao $ 
 * @package 
 */
class OpenBaseController extends Controller{
	
	public $app = array();
	public $appid = 0;
	
	public  function beforeAction($handlerAdapter) {
		parent::beforeAction($handlerAdapter);
		$charset = 'utf-8';
		$_windidkey = $request->get('windidkey', 'get');
		$_time = (int)$request->get('time', 'get');
		$_clientid = (int)$request->get('clientid', 'get');
		if (!$_time || !$_clientid) $this->output(WindidError::FAIL);
		$clent = $this->_getAppDs()->getApp($_clientid);
		if (!$clent) $this->output(WindidError::FAIL);
		if (WindidUtility::appKey($clent['id'], $_time, $clent['secretkey'], $request->getGet(null), $request->getPost()) != $_windidkey)  $this->output(WindidError::FAIL);
		
		$time = Tool::getTime();
		if ($time - $_time > 1200) $this->output(WindidError::TIMEOUT);
		$this->appid = $_clientid;
	}
	
	protected function setDefaultTemplateName($handlerAdapter) {
		return view('');
	}
	
	public function run() {
		$this->output(0);
	}
	
	protected function output($message = '') {
		if (is_numeric($message)) {
			echo $message;
			exit;
		} else {
			header('Content-type: application/json; charset=' . Core::V('charset'));
			echo Tool::jsonEncode($message);
			exit;
		}
	}
	
	private function _getAppDs() {
		return app('WSRV:app.WindidApp');
	}
}
?>