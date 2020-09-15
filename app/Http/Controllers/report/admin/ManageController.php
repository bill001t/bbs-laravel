<?php
Wind::import('ADMIN:library.AdminBaseController');
Wind::import('SRV:report.dm.PwReportDm');

/**
 * 举报管理
 *
 * @author jinlong.panjl <jinlong.panjl@aliyun-inc.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id$
 * @package wind
 */
class ManageController extends AdminBaseController {
	private $perpage = 20;
	private $_maxUids = 30;
	
	/**
	 * 举报管理
	 *
	 * @return void
	 */
	public function run() {
		list($page, $perpage, $ifcheck, $type) = $request->get(array('page', 'perpage', 'ifcheck', 'type'));
		$page = $page ? $page : 1;
		$perpage = $perpage ? $perpage : $this->perpage;
		list($start, $limit) = Tool::page2limit($page, $perpage);
		
		$count = $this->_getReportDs()->countByType($ifcheck, $type);
		if ($count) {
			$reports = $this->_getReportService()->getReceiverList($ifcheck, $type, $limit, $start);
		}
		$reportTypes = $this->_getReportService()->getTypeName();
		->with($reportTypes, 'reportTypes');
		->with($page, 'page');
		->with($perpage, 'perpage');
		->with($count, 'count');
		->with($reports, 'reports');
		->with(array('ifcheck' => $ifcheck, 'type' => $type), 'args');
	}
		
	/**
	 * 忽略
	 *
	 * @return void
	 */
	public function deleteAction(Request $request) {
		$id = $request->get('id', 'post');
		if (!$id) return $this->showError('operate.select');
		!is_array($id) && $id = array($id);
		$this->_sendDealNotice($id,'忽略');
		$this->_getReportDs()->batchDeleteReport($id);
		return $this->showMessage('success');
	}
	
	private function _buildNoticeTitle($username,$action) {
		return '您举报的内容已被 <a href="' . url('space/index/run', array('username' => $username), '', 'pw') .'">' . $username . '</a> '.$action.'，感谢您能一起协助我们管理站点。';
	}
	
	/**
	 * 标记处理
	 *
	 * @return void
	 */
	public function dealCheckAction(Request $request) {
		$id = $request->get('id', 'post');
		if (!$id) return $this->showError('operate.select');
		!is_array($id) && $id = array($id);
		$dm = new PwReportDm();
		$dm->setOperateUserid($this->loginUser->uid)
			->setOperateTime(Tool::getTime())
			->setIfcheck(1);
		$this->_getReportDs()->batchUpdateReport($id,$dm);
		$this->_sendDealNotice($id,'处理');
		return $this->showMessage('success');
	}
	
	private function _sendDealNotice($ids,$action) {
		$reports = $this->_getReportDs()->fetchReport($ids);
		$notice = app('message.srv.PwNoticeService');
		$extendParams = array(
			'operateUserId' => $this->loginUser->uid,
			'operateUsername' => $this->loginUser->username,
			'operateTime' => Tool::getTime(),
			'operateType' => $action,
		); 
		foreach ($reports as $v) {
			$this->_getReportService()->sendNotice($v,$extendParams);
			$content = $this->_buildNoticeTitle($this->loginUser->username,$action);
			$action == '处理' && $this->_getPwNoticeService()->sendDefaultNotice($v['created_userid'],$content,$content);
		}
		return true;
	}
	
	/**
	 * 接收提醒用户列表
	 *
	 * @return void
	 */
	public function receiverListAction(Request $request) {
		$uids = $this->_getReportDs()->getNoticeReceiver();
		$receivers = $this->getUsersWithGroup($uids);
		$this->setOutPut($receivers, 'receivers');
	}
	
	/**
	 * 添加接收人
	 *
	 * @return void
	 */
	public function addReceiverAction(Request $request) {
		$username = $request->get('username', 'post');
		!$username && return $this->showError('Report:user.empty');
		$user = $this->_getPwUserDs()->getUserByName($username);
		if (!$user) {
			return $this->showError('Report:user.not.presence');
		}
		$uids = $this->_getReportDs()->getNoticeReceiver();
		if (count($uids) >= $this->_maxUids) {
			return $this->showError('REPORT:receiver.num.error');
		}
		!in_array($user['uid'], $uids) && $uids[] = $user['uid'];
		$config = new PwConfigSet('report');
		$config->set('noticeReceiver', $uids)
				->flush();
		return $this->showMessage('success');
	}
		
	/**
	 * do删除
	 *
	 * @return void
	 */
	public function deleteReceiverAction(Request $request) {
		$uid = (int)$request->get('uid', 'post');
		if (!$uid) {
			return $this->showError('operate.fail');
		}

		$uids = $this->_getReportDs()->getNoticeReceiver();
		$uids = array_flip($uids);
		unset($uids[$uid]);
		$config = new PwConfigSet('report');
		$config->set('noticeReceiver', array_keys($uids))
				->flush();
		return $this->showMessage('success');
	}

	/**
	 * 根据用户uids批量获取用户带身份
	 * 
	 * @param array $uids
	 * @return array
	 */
	private function getUsersWithGroup($uids) {
		if (!is_array($uids) || !count($uids)) {
			return array();
		}
		$users = $this->_getPwUserDs()->fetchUserByUid($uids, PwUser::FETCH_MAIN);
		$gids = $receivers = array();
		foreach ($users as $v) {
			$gids[$v['uid']] = ($v['groupid'] == 0) ? $v['memberid'] : $v['groupid'];
		}
		$groupDs = app('usergroup.PwUserGroups');
		$groups = $groupDs->fetchGroup($gids);
		foreach ($users as $k => $v) {
			$gid = ($v['groupid'] == 0) ? $v['memberid'] : $v['groupid'];
			$user['username'] = $v['username'];
			$user['uid'] = $v['uid'];
			$user['group'] = $groups[$gid]['name'];
			$receivers[] =  $user;
		}
		return $receivers;
	}

	/** 
	 * @return PwNoticeService
	 */
	protected function _getPwNoticeService(){
		return app('message.srv.PwNoticeService');
	}
	
	/** 
	 * @return PwReportReceiverDs
	 */
	protected function _getReportReceiverDs(){
		return app('report.PwReportReceiver');
	}
	
	/** 
	 * @return PwReportDs
	 */
	protected function _getReportDs(){
		return app('report.PwReport');
	}
	
	/** 
	 * @return PwReportService
	 */
	protected function _getReportService(){
		return app('report.srv.PwReportService');
	}
	
	/**
	 * @return PwUserDs
	 */
	protected function _getPwUserDs(){
		return app('user.PwUser');
	}
}
?>