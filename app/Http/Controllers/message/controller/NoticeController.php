<?php

class NoticeController extends Controller{

	public function beforeAction($handlerAdapter){
		parent::beforeAction($handlerAdapter);
		if (!$this->loginUser->isExists()) {
			return redirect('u/login/run'));
		}
		$action = $handlerAdapter->getAction();
		$controller = $handlerAdapter->getController();
		->with($action,'_action');
		->with($controller,'_controller');
	}

	public function run() {
		list($type,$page) = $request->get(array('type','page'));
		$page = intval($page);
		$page < 1 && $page = 1;
		$perpage = 20;
		list($start, $limit) = Tool::page2limit($page, $perpage);
		$noticeList = $this->_getNoticeDs()->getNotices($this->loginUser->uid,$type,$start, $limit);
		$noticeList = $this->_getNoticeService()->formatNoticeList($noticeList);
		$typeCounts = $this->_getNoticeService()->countNoticesByType($this->loginUser->uid);
		//类型
		$typeid = intval($type);
		//获取未读通知数
		$unreadCount = $this->_getNoticeDs()->getUnreadNoticeCount($this->loginUser->uid);

		$this->_readNoticeList($unreadCount,$noticeList);

		//count
		$count = intval($typeCounts[$typeid]['count']);
		->with($page, 'page');
		->with($perpage, 'perpage');
		->with($count, 'count');
		->with(ceil($count/$perpage), 'totalpage');
		->with(array('type'=>$typeid),'args');
		->with($typeid, 'typeid');
		->with($typeCounts, 'typeCounts');
		->with($noticeList, 'noticeList');

		// seo设置
		Wind::import('SRV:seo.bo.PwSeoBo');
		$seoBo = PwSeoBo::getInstance();
		$lang = Wind::getComponent('i18n');
		$seoBo->setCustomSeo($lang->getMessage('SEO:mess.notice.run.title'), '', '');
		Core::setV('seo', $seoBo);
	}

	/**
	 *
	 * 忽略消息
	 */
	public function ignoreAction(Request $request){
		list($id,$ignore) = $request->get(array('id','ignore'));
		if ($this->_getNoticeService()->ignoreNotice($id,$ignore)) {
			return $this->showMessage('操作成功');
		} else {
			return $this->showError('操作失败');
		}
	}

	/**
	 *
	 * 删除消息
	 */
	public function deleteAction(Request $request){
		list($id,$ids) = $request->get(array('id','ids'), 'post');
		if (!$ids && $id) $ids = array(intval($id));
        if(!is_array($ids))return $this->showError('操作失败');
		if ($this->_getNoticeDs()->deleteNoticeByIdsAndUid($this->loginUser->uid, $ids)) {
			return $this->showMessage('操作成功');
		} else {
			return $this->showError('操作失败');
		}
	}

	/**
	 *
	 * 顶部快捷列表
	 */
	public function minilistAction(Request $request){
		$perpage = 20;
		$noticeList = $this->_getNoticeDs()->getNoticesOrderByRead($this->loginUser->uid, $perpage);
		$noticeList = $this->_getNoticeService()->formatNoticeList($noticeList);
		//获取未读通知数
		$unreadCount = $this->_getNoticeDs()->getUnreadNoticeCount($this->loginUser->uid);
		$this->_readNoticeList($unreadCount,$noticeList);
		//set layout for common request
		if (!$request->getIsAjaxRequest()){
			$this->setLayout('layout_notice_minilist');
		}
		->with($noticeList, 'noticeList');
	}

	/**
	 *
	 * 具体通知详细页
	 */
	public function detaillistAction(Request $request){
		$id = $request->get('id');
		$notice = $this->_getNoticeDs()->getNotice($id);
		if (!$notice || $notice['uid'] != $this->loginUser->uid) {
			return $this->showError('获取内容失败');
		}

		$detailList = $this->_getNoticeService()->getDetailList($notice);
		->with($notice, 'notice');
		->with($detailList,'detailList');
		$typeName = $this->_getNoticeService()->getTypenameByTypeid($notice['typeid']);
		->with($typeName, 'typeName');
		//$tpl = $typeName ? sprintf('notice_detail_%s',$typeName) : 'notice_detail';
		//return view($tpl);
	}

	/**
	 *
	 * 具体通知详细页
	 */
	public function detailAction(Request $request){
		$id = $request->get('id');
		$notice = $this->_getNoticeDs()->getNotice($id);
		if (!$notice || $notice['uid'] != $this->loginUser->uid) {
			return $this->showError('获取内容失败');
		}
		$prevNotice = $this->_getNoticeDs()->getPrevNotice($this->loginUser->uid,$id);
		$nextNotice = $this->_getNoticeDs()->getNextNotice($this->loginUser->uid,$id);
		$detailList = $this->_getNoticeService()->getDetailList($notice);
		->with($notice, 'notice');
		->with($detailList,'detailList');
		->with($prevNotice, 'prevNotice');
		->with($nextNotice, 'nextNotice');
		$typeName = $this->_getNoticeService()->getTypenameByTypeid($notice['typeid']);
		->with($typeName, 'typeName');
		//$tpl = $typeName ? sprintf('notice_detail_%s',$typeName) : 'notice_detail';
		//return view($tpl);
	}

	/**
	 *
	 * Enter description here ...
	 * @return PwMessageNotices
	 */
	protected function _getNoticeDs(){
		return app('message.PwMessageNotices');
	}

	/**
	 *
	 * Enter description here ...
	 * @return PwNoticeService
	 */
	protected function _getNoticeService(){
		return app('message.srv.PwNoticeService');
	}

	/**
	 *
	 * Enter description here ...
	 * @return PwUser
	 */
	protected function _getUserDs(){
		return app('user.PwUser');
	}

	/**
	 *
	 * 设置已读
	 * @param int $unreadCount
	 * @param array $noticeList
	 */
	private function _readNoticeList($unreadCount,$noticeList){
		if ($unreadCount && $noticeList) {
			//更新用户的通知未读数
			$readnum = 0; //本次阅读数
			Wind::import('SRV:message.dm.PwMessageNoticesDm');
			$dm = new PwMessageNoticesDm();
			$dm->setRead(1);
			$ids = array();
			foreach ($noticeList as $v) {
				if ($v['is_read']) continue;
				$readnum ++;
				$ids[] = $v['id'];
			}
			$ids && $this->_getNoticeDs()->batchUpdateNotice($ids,$dm);
			$newUnreadCount = $unreadCount - $readnum;
			if ($newUnreadCount != $unreadCount) {
				Wind::import('SRV:user.dm.PwUserInfoDm');
				$dm = new PwUserInfoDm($this->loginUser->uid);
				$dm->setNoticeCount($newUnreadCount);
				$this->_getUserDs()->editUser($dm,PwUser::FETCH_DATA);
			}
		}
	}
}