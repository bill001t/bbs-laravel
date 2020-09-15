<?php
Wind::import('APPS:api.controller.OpenBaseController');

/**
 * the last known user to change this file in the repository  <$LastChangedBy: jieyin $>
 * @author $Author: jieyin $ Foxsee@aliyun.com
 * @copyright ?2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: MessageController.php 24706 2013-02-16 06:02:32Z jieyin $ 
 * @package 
 */

class MessageController extends OpenBaseController {
	
	public function getMessageByIdAction(Request $request) {
		$result = $this->_getMessageDs()->getMessageById($request->get('messageId', 'get'));
		$this->output($result);
	}

	public function getNumAction(Request $request) {
		$result = $this->_getMessageService()->getUnRead($request->get('uid', 'get'));
		$this->output($result);
	}

	public function countMessageAction(Request $request) {
		$result = $this->_getMessageDs()->countRelation($request->get('dialogId', 'get'));
		$this->output($result);
	}
	
	public function getMessageListAction(Request $request) {
		$dialogId = $request->get('dialogId', 'get');
		$start = (int)$request->get('start', 'get');
		$limit = $request->get('limit', 'get');
		!$limit && $limit = 10;
		$result = $this->_getMessageDs()->getDialogMessages($dialogId, $start, $limit);
		$this->output($result);
	}
	
	public function getDialogAction(Request $request) {
		$result = $this->_getMessageDs()->getDialog($request->get('dialogId', 'get'));
		$this->output($result);
	}
	
	public function fetchDialogAction(Request $request) {
		$result = $this->_getMessageDs()->fetchDialog($request->get('dialogIds', 'get'));
		$this->output($result);
	}
	
	public function getDialogByUserAction(Request $request) {
		$uid = $request->get('uid', 'get');
		$dialogUid = $request->get('dialogUid', 'get');
		$result = $this->_getMessageDs()->getDialogByUid($uid, $dialogUid);
		$this->output($result);
	}
	
	public function getDialogByUsersAction(Request $request) {
		$uid = $request->get('uid', 'get');
		$dialogUids = $request->get('dialogUids', 'get');
		$result = $this->_getMessageDs()->getDialogByUids($uid, $dialogUids);
		$this->output($result);
	}
	
	public function getDialogListAction(Request $request) {
		$uid = $request->get('uid', 'get');
		$start = (int)$request->get('start', 'get');
		$limit = (int)$request->get('limit', 'get');
		!$limit && $limit = 10;
		$result = $this->_getMessageDs()->getDialogs($uid, $start, $limit);
		$this->output($result);
	}
	
	public function countDialogAction(Request $request) {
		$uid = (int)$request->get('uid', 'get');
		$result = $this->_getMessageDs()->countDialogs($uid);
		$this->output($result);
	}
	
	/**
	 * 搜索消息
	 * 
	 * @return array(count, list)
	 */
	public function searchMessageAction(Request $request) {
		$start = (int)$request->get('start' , 'get');
		$limit = (int)$request->get('limit', 'get');
		list($fromuid, $keyword, $username, $starttime, $endtime) = $request->get(array('fromuid', 'keyword', 'username', 'starttime', 'endtime'), 'get');

		$search = array();
		isset($fromuid) && $search['fromuid'] = $fromuid;
		isset($keyword) && $search['keyword'] = $keyword;
		isset($username) && $search['username'] = $username;
		isset($starttime) && $search['starttime'] = $starttime;
		isset($endtime) && $search['endtime'] = $endtime;
		!$limit && $limit = 10;
		$result = $this->_getMessageService()->searchMessage($search, $start, $limit);
		$this->output($result);
	}
	
	public function editNumAction(Request $request) {
		$uid = (int)$request->get('uid', 'post');
		$num = (int)$request->get('num', 'post');
		$result = $this->_getMessageService()->editMessageNum($uid, $num);
		$this->_getNotifyService()->send('editMessageNum', array('uid' => $uid), $this->appid);
		$this->output(WindidUtility::result($result));
	}
	
	public function sendAction(Request $request) {
		$uids = $request->get('uids', 'post');
		$content = $request->get('content', 'post');
		$fromUid = $request->get('fromUid', 'post');

		is_array($uids) || $uids = array($uids);
		$result = $this->_getMessageService()->sendMessageByUids($uids, $content, $fromUid);
		if ($result instanceof WindidError) {
			$this->output($result->getCode());
		}
		$srv = $this->_getNotifyService();
		foreach ($uids as $uid) {
			$srv->send('editMessageNum', array('uid' => $uid), $this->appid);
		}
		$this->output(WindidUtility::result($result));
	}
	
	public function readAction(Request $request) {
		$messageIds = $request->get('messageIds', 'post');
		$dialogId = $request->get('dialogId', 'post');
		$uid = $request->get('uid', 'post');
		$result = $this->_getMessageService()->read($uid, $dialogId, $messageIds);
		if ($result) {
			$this->_getNotifyService()->send('editMessageNum', array('uid' => $uid), $this->appid);
		} 
		$this->output($result);
	}
	
	public function readDialogAction(Request $request) {
		$result = $this->_getMessageService()->readDialog($request->get('dialogIds', 'post'));
		$ds = $this->_getMessageDs();
		foreach ($dialogIds as $id) {
			$dialog = $ds->getDialog($id);
			$this->_getNotifyService()->send('editMessageNum', array('uid' => $dialog['to_uid']), $this->appid);
		}
		$this->output(WindidUtility::result($result));
	}
	
	public function deleteAction(Request $request) {
		$messageIds = $request->get('messageIds', 'post');
		$dialogId = $request->get('dialogId', 'post');
		$uid = $request->get('uid', 'post');
		$result = $this->_getMessageService()->delete($uid, $dialogId, $messageIds);
		if ($result) {
			$this->_getNotifyService()->send('editMessageNum', array('uid' => $uid), $this->appid);
		}
		$this->output(WindidUtility::result($result));
	}
	
	public function batchDeleteDialogAction(Request $request) {
		$dialogIds = $request->get('dialogIds', 'post');
		$uid = $request->get('uid', 'post');
		$result = $this->_getMessageService()->batchDeleteDialog($uid, $dialogIds);
		$this->_getNotifyService()->send('editMessageNum', array('uid' => $uid), $this->appid);
		$this->output(WindidUtility::result($result));
	}
	
	public function deleteByMessageIdsAction(Request $request) {
		$result = $this->_getMessageService()->deleteByMessageIds($request->get('messageIds', 'post'));
		$this->output(WindidUtility::result($result));
	}
	
	public function deleteUserMessagesAction(Request $request) {
		$uid = (int)$request->get('uid', 'post');
		$result = $this->_getMessageService()->deleteUserMessages($uid);
		$this->_getNotifyService()->send('editMessageNum', array('uid' => $uid), $this->appid);
		$this->output(WindidUtility::result($result));
	}
	
	/********************** 传统收件箱，发件箱接口start *********************/
	
	public function fromBox() {
		$uid = (int)$request->get('uid', 'get');
		$start = (int)$request->get('start', 'get');
		$limit = (int)$request->get('limit', 'get');
		!$limit && $limit = 10;
		!$start && $start = 0;
		$result = $this->_getBoxMessage()->fromBox($uid, $start, $limit);
		$this->output($result);
	}

	public function toBox() {
		$uid = (int)$request->get('uid', 'get');
		$start = (int)$request->get('start', 'get');
		$limit = (int)$request->get('limit', 'get');
		!$limit && $limit = 10;
		!$start && $start = 0;
		$result = $this->_getBoxMessage()->toBox($uid, $start, $limit);
		$this->output($result);
	}
	
	public function readMessages() {
		$uid = (int)$request->get('uid', 'post');
		$messageIds = $request->get('messageIds', 'post');
		if (!is_array($messageIds)) $messageIds = array($messageIds);
		$result = $this->_getBoxMessage()->readMessages($uid, $messageIds);
		$this->output(WindidUtility::result($result));
	}
	
	public function deleteMessages() {
		$uid = (int)$request->get('uid', 'post');
		$messageIds = $request->get('messageIds', 'post');
		if (!is_array($messageIds)) $messageIds = array($messageIds);
		$result = $this->_getBoxMessage()->deleteMessages($uid, $messageIds);
		$this->output(WindidUtility::result($result));
	}
	
	/********************** 传统收件箱，发件箱接口end *********************/
	
	private function _getMessageDs() {
		return app('WSRV:message.WindidMessage');
	}
	
	private function _getMessageService() {
		return app('WSRV:message.srv.WindidMessageService');
	}
	
	private function _getBoxMessage() {
		return app('WSRV:message.srv.WindidBoxMessage');
	}
	
	private function _getNotifyService() {
		return app('WSRV:notify.srv.WindidNotifyService');
	}
}
?>