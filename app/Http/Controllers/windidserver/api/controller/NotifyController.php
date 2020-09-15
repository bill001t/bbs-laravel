<?php
Wind::import('APPS:api.controller.OpenBaseController');
/**
 * the last known user to change this file in the repository  <$LastChangedBy: gao.wanggao $>
 * @author $Author: gao.wanggao $ Foxsee@aliyun.com
 * @copyright ?2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: NotifyController.php 24579 2013-02-01 03:26:06Z gao.wanggao $ 
 * @package 
 */
class NotifyController extends OpenBaseController{
	
	public function fetchAction(Request $request) {
		$result = $this->_getNotifyDs()->fetchNotify($request->get('nids', 'get'));
		$this->output($result);
	}

	public function batchNotDeleteAction(Request $request) {
		$result = $this->_getNotifyDs()->batchNotDelete($request->get('nids', 'post'));
		$this->output($result);
	}

	public function getlogListAction(Request $request) {
		$appid = (int)$request->get('appid', 'get');
		$nid = (int)$request->get('nid', 'get');
		$limit = (int)$request->get('limit', 'get');
		$start = (int)$request->get('start', 'get');
		$completet = $request->get('completet', 'get');
		
		$result = $this->_getNotifyLogDs()->getList($appid, $nid, $limit, $start, $complete);
		$this->output($result);

	}

	public function countLogListAction(Request $request) {
		$appid = (int)$request->get('appid', 'get');
		$nid = (int)$request->get('nid', 'get');
		$completet = $request->get('completet', 'get');
		$result = $this->_getNotifyLogDs()->countList($appid, $nid, $complete);
		$this->output($result);
	}

	public function deleteLogCompleteAction(Request $request) {
		$result = $this->_getNotifyLogDs()->deleteComplete();
		$this->output($result);
	}
	
	public function deleteLogAction(Request $request) {
		$result = $this->_getNotifyLogDs()->deleteLog($request->get('logid', 'post'));
		$this->output($result);
	}
	
	public function logSendAction($logid) {
		$result =$this->_getNotifyService()->logSend($request->get('logid', 'post'));
		$this->output($result);
	}
	
	private function _getNotifyDs() {
		return app('WSRV:notify.WindidNotify');
	}

	private function _getNotifyLogDs() {
		return app('WSRV:notify.WindidNotifyLog');
	}
	
	private function _getNotifyService() {
		return app('WSRV:notify.srv.WindidNotifyServer');
	}
}
?>