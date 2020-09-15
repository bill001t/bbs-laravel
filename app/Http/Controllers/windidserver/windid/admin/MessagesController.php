<?php
Wind::import('APPS:windid.admin.WindidBaseController');
/**
 * the last known user to change this file in the repository  <$LastChangedBy: jieyin $>
 * @author $Author: jieyin $ Foxsee@aliyun.com
 * @copyright ?2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: MessagesController.php 23833 2013-01-16 06:41:42Z jieyin $ 
 * @package 
 */
class MessagesController extends WindidBaseController { 
	private $perpage = 20;
	private $perstep = 10;
	
	public function run() {
		list($page, $perpage, $username, $starttime, $endtime, $keyword) = $request->get(array('page', 'perpage', 'username', 'starttime', 'endtime', 'keyword'));
		$starttime && $pwStartTime = Tool::str2time($starttime);
		$endtime && $pwEndTime = Tool::str2time($endtime);
		$page = $page ? $page : 1;
		$perpage = $perpage ? $perpage : $this->perpage;
		list($start, $limit) = Tool::page2limit($page, $perpage);
		if ($username) {
			$userinfo = $this->_getUserDs()->getUserByName($username);
			$fromUid = $userinfo['uid'] ? $userinfo['uid'] : 0;
		}
		Wind::import('WSRV:message.srv.vo.WindidMessageSo');
		$vo = new WindidMessageSo();
		$endtime && $vo->setEndTime($endtime);
		$fromUid && $vo->setFromUid($fromUid);
		$keyword && $vo->setKeyword($keyword);
		$starttime && $vo->setStarttime($starttime);
		$messages = $this->_getMessageDs()->searchMessage($vo, $start, $limit);
		$count = $this->_getMessageDs()->countMessage($vo);
		foreach ($messages AS $k=>$v) {
			$uids[] = $v['from_uid'];
		}
		$users = $this->_getUserDs()->fetchUserByUid($uids);
		foreach ($messages AS $k=>$v) {
			$messages[$k]['username'] = $users[$v['from_uid']]['username'];
		}
		
		->with($count, 'count');
		->with($page, 'page');
		->with($perpage, 'perpage');
		->with(array('keyword' => $keyword, 'username' => $username, 'starttime' => $starttime, 'endtime' => $endtime), 'args');
		->with($messages, 'messages');
	}
	
	/**
	 * 删除消息
	 *
	 * @return void
	 */
	public function deleteMessagesAction(Request $request) {
		$ids = $request->get('ids', 'post');
		if (!$ids) {
			return $this->showError('WINDID:fail');
		} 
		$this->_getMessageService()->deleteByMessageIds($ids);

		return $this->showMessage('WINDID:success');
	}
	
	
	private function _getMessageService() {
		return app('WSRV:message.srv.WindidMessageService');
	}
	
	private function _getMessageDs() {
		return app('WSRV:message.WindidMessage');
	}
	/**
	 * 
	 * Enter description here ...
	 * @return PwUser
	 */
	private function _getUserDs(){
		return app('WSRV:user.WindidUser');
	}
}
?>