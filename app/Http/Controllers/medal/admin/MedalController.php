<?php
Wind::import('ADMIN:library.AdminBaseController');

/**
 * the last known user to change this file in the repository  <$LastChangedBy: taishici $>
 * @author $Author: taishici $ Foxsee@aliyun.com
 * @copyright ?2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: MedalController.php 29742 2013-06-28 08:02:34Z taishici $
 * @package
 */
 class MedalController extends AdminBaseController{
 	/**
 	 * 勋章管理
 	 * @see wekit/wind/web/WindController::run()
 	 */
 	public function run() {
 		$page = (int)$request->get('page','get');
		$perpage = 10;
		$page =  $page > 1 ? $page : 1;
		$count = $this->_getMedalDs()->countInfo();
		list($start, $perpage) = Tool::page2limit($page, $perpage);
 		$medalList = $this->_getMedalDs()->getInfoList(0, 0, $start, $perpage);
 		$sevice = $this->_getMedalService();
 		foreach ($medalList AS &$medal) {
 			$medal['medalImage'] = $sevice->getMedalImage($medal['path'], $medal['icon']);
 		}
 		->with($medalList, 'medalList');
 		->with($count, 'count');
		->with($page, 'page');
		->with($perpage, 'perpage');
		->with(ceil($count/$perpage), 'totalpage');
 	}

 	/**
 	 * 勋章批量修改
 	 *
 	 */
 	public function dorunAction(Request $request) {
 		list($medalIds, $ispoens, $orderids, $names, $descrips) = $request->get(array('medalid', 'isopen', 'orderid', 'name', 'descrip'), 'post');
		Wind::import('SRV:medal.dm.PwMedalDm');
 		foreach ($medalIds AS $medalId) {
			$dm = new PwMedalDm($medalId);
 			$dm->setMedalName($names[$medalId])
				->setDescrip($descrips[$medalId])
				->setIsopen($ispoens[$medalId])
				->setVieworder($orderids[$medalId]);
			$resource = $this->_getMedalDs()->updateInfo($dm);
			if ($resource instanceof ErrorBag) return $this->showError($resource->getError());
		}
		$this->_getMedalService()->updateCache();
		return $this->showMessage("MEDAL:success");
 	}

 	/**
 	 *
 	 * 勋章添加表单
 	 */
 	public function addAction(Request $request) {
 		$userGroup = app('usergroup.PwUserGroups');
		$groups = $userGroup->getAllGroups();
		$groupTypes = $userGroup->getTypeNames();
		$medalList = $this->_getMedalDs()->getInfoList(1, 1, 0, 20);

		//组装json数据
		$medals = $this->_getMedalDs()->getAllOpenMedal();
 		$medalJson = array();
		$i = 1;
		foreach ($medals AS $medal) {
			$_medal = array(
				'order'=>$i,
				'amount'=>$medal['award_condition'],
				'name'=>$medal['name'],
				);
			$medalJson[$medal['award_type']][] = $_medal;
			$i++;
		}
		$lang = Wind::getComponent('i18n');
		$awardTypes = $this->_getMedalService()->awardTypes();
		foreach ($awardTypes AS $key=>$awardType) {
			$awardTypes[$key] = $lang->getMessage("MEDAL:awardtype.".$awardType);
		}
		->with($groups, 'groups');
		->with($groupTypes, 'groupTypes');
		->with($awardTypes, 'awardTypes');
		->with($medalList, 'medalList');
		->with($medalJson, 'medalJson');
 	}

 	/**
 	 * 勋章添加处理
 	 *
 	 */
 	public function doAddAction(Request $request) {
 		Wind::import('SRV:medal.dm.PwMedalDm');
 		if ($this->_getMedalDs()->countInfo() > 100) return $this->showError("MEDAL:medal.count.max");
 		$expired = (int)$request->get('expired','post');
 		$awardtype = $request->get('awardtype','post');
 		$receivetype = $request->get('receivetype','post');
 		$condition = $request->get('awardcondition','post');
 		$image = $this->_uploadImage('image');
 		$icon = $this->_uploadImage('icon');
 		$dm = new PwMedalDm();
 		if ($receivetype == 1 ) $expired = 0;
 		if ($receivetype == 1 && in_array($awardtype, array(1,2,3))) $expired = 3; //连续行为勋章有效期3天,更新行为，延长有效期
 		if ($receivetype == 2){
 			$awardtype = 0;
 			$condition = 0;
 		}
 		$dm->setMedalName($request->get('medalname','post'))
			->setDescrip($request->get('descrip','post'))
			->setMedalGids($request->get('visitGid','post'))
			->setReceiveType($receivetype)
			->setAwardCondition($condition)
			->setAwardType($awardtype)
			->setExpiredDays($expired)
			->setMedalType(2)
			->setIsopen(1);
		if ($image) {
			$dm->setImage($image['filename'])
				->setPath($image['path']);
		}
		if ($icon) {
			$dm->setIcon($icon['filename'])
				->setPath($icon['path']);
		}
		$resource = $this->_getMedalDs()->addInfo($dm);
		if ($resource instanceof ErrorBag) return $this->showError($resource->getError());
		$this->_getMedalService()->updateCache();
		return $this->showMessage("MEDAL:success");
 	}

 	/**
 	 * 勋章编辑表单
 	 *
 	 */
 	public function editAction(Request $request) {
 		$medalId = (int)$request->get('id','get');
 		if ($medalId <1) return $this->showError('MEDAL:fail');
 		$info = $this->_getMedalDs()->getMedalInfo($medalId);
 		$info['icon'] = $this->_getMedalService()->getMedalImage($info['path'], $info['icon']);
 		$info['image'] = $this->_getMedalService()->getMedalImage($info['path'], $info['image']);
 		$userGroup = app('usergroup.PwUserGroups');
		$groups = $userGroup->getAllGroups();
		$groupTypes = $userGroup->getTypeNames();
		$medalList = $this->_getMedalDs()->getInfoListByAwardtype($info['award_type'], 1);

 		//组装json数据
		$medals = $this->_getMedalDs()->getAllOpenMedal();
 		$medalJson = array();
		$i = 1;
		foreach ($medals AS $medal) {
			$_medal = array(
				'order'=>$i,
				'amount'=>$medal['award_condition'],
				'name'=>$medal['name'],
				);
			$medalJson[$medal['award_type']][] = $_medal;
			$i++;
		}
 		$lang =  Wind::getComponent('i18n');
		$awardTypes = $this->_getMedalService()->awardTypes();
		foreach ($awardTypes AS $key=>$awardType) {
			$awardTypes[$key] = $lang->getMessage("MEDAL:awardtype.".$awardType);
		}
		->with($groups, 'groups');
		->with($groupTypes, 'groupTypes');
		->with($awardTypes, 'awardTypes');
		->with($medalList, 'medalList');
		->with($info, 'info');
		->with($medalJson, 'medalJson');
 	}

 	/**
 	 * 勋章修改处理
 	 *
 	 */
 	public function doEditAction(Request $request) {
 		$medalid = (int)$request->get('medalid','post');
 		$expired = (int)$request->get('expired','post');
 		$awardtype = (int)$request->get('awardtype','post');
 		$receivetype = $request->get('receivetype','post');
 		$condition = $request->get('awardcondition','post');
 		if ($_FILES['image']['size']){
 			$image = $this->_uploadImage('image');
 		}
 		if ($_FILES['icon']['size']) {
 			$icon = $this->_uploadImage('icon');
 		}
 		$info = $this->_getMedalDs()->getMedalInfo($medalid);
 		if ($receivetype == 1 ) $expired = 0;
 		if ($receivetype == 1 && in_array($awardtype, array(1,2,3))) $expired = 3;
 		if ($receivetype == 2){
 			$awardtype = 0;
 			$condition = 0;
 		}
 		Wind::import('SRV:medal.dm.PwMedalDm');
 		$dm = new PwMedalDm($medalid);
 		$dm->setMedalName($request->get('medalname','post'))
			->setDescrip($request->get('descrip','post'))
			->setMedalGids($request->get('visitGid','post'))
			->setReceiveType($receivetype)
			->setAwardCondition($condition)
			->setAwardType($awardtype)
			->setExpiredDays($expired);
 		if ($image) {
	 		if ($info['path']) {
	 			Tool::deleteAttach($info['path'] . $info['image']);
	 		}
			$dm->setImage($image['filename'])
				->setPath($image['path']);
		}
		if ($icon) {
			if ($info['path']) {
	 			Tool::deleteAttach($info['path'] . $info['icon']);
	 		}
			$dm->setIcon($icon['filename'])
				->setPath($image['path']);
		}
		$resource = $this->_getMedalDs()->updateInfo($dm);
		if ($resource instanceof ErrorBag) return $this->showError($resource->getError());
		$this->_getMedalService()->updateCache();
		return $this->showMessage("MEDAL:success");
 	}

 	/**
 	 * 勋章删除处理
 	 *
 	 */
 	public function doDelAction(Request $request) {
 		$medalId = (int)$request->get('id','post');
 		if ($medalId <1) return $this->showError('MEDAL:fail');
 		$info = $this->_getMedalDs()->getMedalInfo($medalId);
 		if ($info['medal_type'] == 1) return $this->showError('MEDAL:fail'); //系统勋章不能删除
 		$this->_getMedalDs()->deleteInfo($medalId);
 		if ($info['path']) {
 			Tool::deleteAttach($info['path'] . $info['image']);
 			Tool::deleteAttach($info['path'] . $info['icon']);
 		}
 		$this->_getMedalLogDs()->deleteInfoByMedalId($medalId);
 		$this->_getMedalService()->updateCache();
 		return $this->showMessage("MEDAL:success");
 	}

 	/**
 	 * 更新所有的勋章统计
 	 * Enter description here ...
 	 */
 	public function doUserMedalAction(Request $request) {
 		if(!ini_get('safe_mode')){
			ignore_user_abort(true);
			set_time_limit(0);
		}
 		$perpage = 500;
		$ds = $this->_getMedalUserDs();
		$count = $ds->countMedalUser();
		if (!$count) return $this->showMessage("MEDAL:success");
		$page = ceil($count/$perpage);
		$service = $this->_getMedalService();
		for ($i = 1; $i <= $page; $i++) {
			list($start, $perpage) = Tool::page2limit($page, $perpage);
			$list = $ds->getMedalUserList($start, $perpage);
			foreach ($list AS $v) {
				$service->updateMedalUser($v['uid']);
			}
			$ds->deleteMedalUsersBycount();
			sleep(1);
		}
		return $this->showMessage("MEDAL:success");
 	}

 	/**
 	 * 勋章颁发列表页
 	 *
 	 */
 	public function awardAction(Request $request) {
 		$_empty = false;
 		$uids = $medalids = $jsonMedals = array();
 		$userDs = app('SRV:user.PwUser');
 		$page = (int)$request->get('page','get');
		$perpage = 20;
		$page =  $page > 1 ? $page : 1;
		$uid = (int)$request->get('uid','get');
		$receivetype = 2;
 		if ($uid < 1) {
 			$medalId = (int)$request->get('medalid');
 			//$receivetype = (int)$request->get('receivetype');
 			$username = $request->get('username');
 			$user = $username ? $userDs->getUserByName($username) : array();
			$uid = isset($user['uid']) ? $user['uid'] : 0;
			if ($username && $uid < 1 ) $_empty = true;
 		}
 		$medalList = $this->_getMedalDs()->getInfoListByReceiveType($receivetype, 1);
 		if ($medalId < 1 && $receivetype > 0) {
 			$_medalIds = array_keys($medalList);
 		} elseif($medalId > 0) {
 			$_medalIds = array($medalId);
 		} else {
 			$_medalIds = array();
 		}

		list($start, $perpage) = Tool::page2limit($page, $perpage);
		$list = $medals = $users = array();
		$count = 0 ;
		if (false == $_empty) {
			$list = $this->_getMedalLogDs()->getMedalLogList($uid, PwMedalLog::STATUS_AWARDED, $_medalIds, $start, $perpage);
			foreach ($list AS $medal){
				$uids[] = $medal['uid'];
				$medalids[] = $medal['medal_id'];
			}
			$users = $userDs->fetchUserByUid($uids);
			$medals = $this->_getMedalDs()->fetchMedalInfo($medalids);
			$sevice = $this->_getMedalService();
			foreach ($medals AS &$medal) {
				$medal['medalImage'] = $sevice->getMedalImage($medal['path'], $medal['icon']);
			}
			$count = $this->_getMedalLogDs()->countMedalLogList($uid,  PwMedalLog::STATUS_AWARDED, $_medalIds);
		}

 		$args = array('medalid' => $medalId, 'receivetype' => $receivetype, 'username' => $username);
		->with($args, 'args');
 		->with($list, 'list');
 		->with($users, 'users');
 		->with($medals, 'medals');
 		->with($count, 'count');
		->with($page, 'page');
		->with($perpage, 'perpage');
		->with($medalList, 'medalList');
		->with($username, 'username');
		->with($medalId, 'medalId');
 	}

 	/**
 	 * 勋章收回
 	 *
 	 */
 	public function doStopAction(Request $request) {
 		$logid = (int)$request->get('logid','post');
 		$resource = $this->_getMedalService()->stopAward($logid, 7);
 		if ($resource instanceof ErrorBag) return $this->showError($resource->getError());
 		return $this->showMessage("MEDAL:success");
 	}

 	/**
 	 * 批量勋章收回
 	 *
 	 */
 	public function batchStopAction(Request $request) {
 		$logids = (array)$request->get('logids','post');
 		foreach ($logids AS $logid) {
 			$this->_getMedalService()->stopAward($logid, 7);
 		}
 		return $this->showMessage("MEDAL:success");
 	}

 	/**
 	 * 勋章颁发
 	 *
 	 */
 	public function addAwardAction(Request $request) {
 		$medalList = $this->_getMedalDs()->getInfoListByReceiveType(2, 1);
 		->with($medalList, 'medalList');
 	}

 	/**
 	 * 管理员批量颁发勋章策略
 	 * Enter description here ...
 	 */
 	public function doAddAwardAction(Request $request) {
 		$username = trim($request->get('username','post'));
 		$medalId = (int)$request->get('medalid','post');
 		$message = $request->get('message','post');
 		if ($medalId < 1) return $this->showError('MEDAL:fail');
 		if (!$username) return $this->showError('MEDAL:award.username.require');
 		$usernames = array_unique(explode(' ', $username));
 		if (count($usernames) < 1) return $this->showError('MEDAL:award.username.require');
 		$userDs = app('SRV:user.PwUser');
 		$users = $userDs->fetchUserByName($usernames);
 		//$users = array_keys($userInfos);
 		if (!is_array($users) || count($users) < 1) return $this->showError('MEDAL:username.fail');

 		$info = $this->_getMedalDs()->getMedalInfo($medalId);
 		if (!$info)  return $this->showError('MEDAL:medal.fail');
 		$time = Tool::getTime();
 		$expired = ($info['receive_type'] == 2 && $info['expired_days'] > 0) ? ($time + $info['expired_days']*24*60) : 0;
 		$userSrv = app('user.srv.PwUserService');
 		$medalSrv = $this->_getMedalService();
 		Wind::import('SRV:medal.dm.PwMedalLogDm');
 		$ds = $this->_getMedalLogDs();
 		$msg = '';
 		foreach ($users AS $user) {
 			if (!$user['uid']) continue;
 			/*$userGids = $userSrv->getGidsByUid($user['uid']);
 			if (!$medalSrv->allowAwardMedal($userGids, $info['medal_gids'])) {
 				$msg .= $user['username'] . '所在用户组不能颁发;';
 				continue;
 			}*/
 			$log = $this->_getMedalLogDs()->getInfoByUidMedalId($user['uid'], $medalId);
 			if (isset($log['award_status']) && $log['award_status'] == 4) {
 				$msg .= $user['username'] . '已拥有该勋章;';
 				continue;
 			}
 			if (isset($log['log_id']) && $log['log_id'] > 1) {
 				$dm = new PwMedalLogDm($log['log_id']);
	 			$dm->setMedalid($medalId)
	 				->setUid($user['uid'])
	 				->setAwardStatus(PwMedalLog::STATUS_AWARD)
	 				->setCreatedTime($time)
	 				->setExpiredTime($expired);
	 			$resource = $ds->updateInfo($dm);
 			} else {
 				$dm = new PwMedalLogDm();
	 			$dm->setMedalid($medalId)
	 				->setUid($user['uid'])
	 				->setAwardStatus(PwMedalLog::STATUS_AWARD)
	 				->setCreatedTime($time)
	 				->setExpiredTime($expired);
	 			$resource = $ds->replaceMedalLog($dm);
 			}
			if (!$resource instanceof ErrorBag) {
				$this->_getMedalService()->updateMedalUser($user['uid']);
				$this->_getMedalService()->sendNotice($user['uid'], $resource, $medalId, 2, $message);
			}
 		}
 		$msg = $msg ? $msg : 'MEDAL:success';
 		return $this->showMessage($msg);
 	}

 	/**
 	 * 审核勋章
 	 *
 	 */
 	public function approvalAction(Request $request){
 		$_empty = false;
 		$uids = $medalids = array();
 		$userDs = app('SRV:user.PwUser');
 		$page = (int)$request->get('page','get');
		$perpage = 10;
		$page =  $page > 1 ? $page : 1;
		$uid = (int)$request->get('uid','get');
		$medalId = (int)$request->get('medalid');
		if ($uid < 1) {
	 		$username = $request->get('username');
	 		$user = $username ? $userDs->getUserByName($username) : array();
			$uid = isset($user['uid']) ? $user['uid'] : 0;
			if ($username && $uid < 1 ) $_empty = true;
		}
		list($start, $perpage) = Tool::page2limit($page, $perpage);
 		$list = $this->_getMedalLogDs()->getInfoList($uid, 2, $medalId, $start, $perpage);
 		foreach ($list AS $medal){
 			$uids[] = $medal['uid'];
 			$medalids[] = $medal['medal_id'];
 		}
 		$users = $userDs->fetchUserByUid($uids);
 		$medals = $this->_getMedalDs()->fetchMedalInfo($medalids);
 		$sevice = $this->_getMedalService();
 		foreach ($medals AS &$medal) {
 			$medal['medalImage'] = $sevice->getMedalImage($medal['path'], $medal['icon']);
 		}
 		$count = $this->_getMedalLogDs()->countInfo($uid, 2, $medalId);

 		$medalList = $this->_getMedalDs()->getInfoListByReceiveType(2, 1);
 		if ($_empty) $list = array();
 		->with($medalList, 'medalList');
 		$args = array('medalid' => $medalId, 'username' => $username);
		->with($args, 'args');
 		->with($list, 'list');
 		->with($users, 'users');
 		->with($medals, 'medals');
 		->with($count, 'count');
		->with($page, 'page');
		->with($perpage, 'perpage');
		->with(ceil($count/$perpage), 'totalpage');
		->with($username, 'username');
		->with($medalId, 'medalId');
 	}

 	/**
 	 * 审核勋章操作
 	 *
 	 */
 	public function doEditApplyAction(Request $request) {
 		$logId = (int)$request->get('id','get');
 		$check = $request->get('check','get');
 		$log = $this->_getMedalLogDs()->getMedalLog($logId);
 		Wind::import('SRV:medal.dm.PwMedalLogDm');
 		$dm = new PwMedalLogDm($logId);
 		$ds = $this->_getMedalLogDs();
 		if ($check == 'yes') {
 			$dm->setAwardStatus(PwMedalLog::STATUS_AWARD);
 			$resource = $ds->updateInfo($dm);
 			if ($resource instanceof ErrorBag) return $this->showError($resource->getError());
 			$this->_getMedalService()->sendNotice($log['uid'], $log['log_id'], $log['medal_id'], 3);
 		}else{
 			//$dm->setAwardStatus(5);
 			$resource = $ds->deleteInfo($logId);
 			$this->_getMedalService()->sendNotice($log['uid'], $log['log_id'], $log['medal_id'], 4);
 			if (! $resource) return $this->showError("MEDAL:fail");
 		}
 		return $this->showMessage("MEDAL:success");
 	}

 	/**
 	 * 勋章申请批量操作
 	 *
 	 */
 	public function batchPassAction(Request $request) {
 		$logids = (array)$request->get('logids','post');
 		Wind::import('SRV:medal.dm.PwMedalLogDm');
 		$ds = $this->_getMedalLogDs();
 		$srv = $this->_getMedalService();
 		foreach ($logids AS $logid) {
 			$log = $ds->getMedalLog($logid);
 			$dm = new PwMedalLogDm($logid);
 			$dm->setAwardStatus(PwMedalLog::STATUS_AWARD);
 			$resource = $ds->updateInfo($dm);
 			if (!$resource instanceof ErrorBag) $srv->sendNotice($log['uid'], $log['log_id'], $log['medal_id'], 3);
 		}
 		return $this->showMessage("MEDAL:success");
 	}

 	public function batchDisclaimAction(Request $request) {
 		$logids = (array)$request->get('logids','post');
 		Wind::import('SRV:medal.dm.PwMedalLogDm');
 		$ds = $this->_getMedalLogDs();
 		$srv = $this->_getMedalService();
 		foreach ($logids AS $logid) {
 			$log = $ds->getMedalLog($logid);
 			$resource = $ds->deleteInfo($logid);
 			if (!$resource instanceof ErrorBag) $srv->sendNotice($log['uid'], $log['log_id'], $log['medal_id'], 4);
 		}
 		return $this->showMessage("MEDAL:success");
 	}

 	public function setAction(Request $request) {
 		$config = Core::C()->getValues('site');
		->with($config, 'config');
 	}

 	public function doSetAction(Request $request) {
 		$config = new PwConfigSet('site');
 		$isopen = (int)$request->get('isopen', 'post');
		$config->set('medal.isopen', $isopen)
			->flush();
		app('SRV:nav.srv.PwNavService')->updateNavOpen('medal', $isopen);
		return $this->showMessage('MEDAL:success');
 	}

 	private function _uploadImage($key = 'image') {
 		Wind::import('SRV:upload.action.PwMedalUpload');
		Wind::import('LIB:upload.PwUpload');
 		if ($key == 'image') {
			$bhv = new PwMedalUpload('image' , 80, 80);
 		} else {
			$bhv = new PwMedalUpload('icon' , 30, 30);
 		}
		$upload = new PwUpload($bhv);
		if (($result = $upload->check()) === true) {
			$result = $upload->execute();
		}
		if ($result !== true) {
			return $this->showError($result->getError());
		}
		return $bhv->getAttachInfo();
 	}

 	/**
 	 * Enter description here ...
 	 *
 	 * @return PwMedalService
 	 */
 	private function _getMedalService() {
		return app('SRV:medal.srv.PwMedalService');
	}

	/**
	 * Enter description here ...
	 *
	 * @return PwMedalInfo
	 */
	private function _getMedalDs() {
		return app('SRV:medal.PwMedalInfo');
	}

 	private function _getMedalUserDs() {
		return app('SRV:medal.PwMedalUser');
	}

 	/**
 	 * Enter description here ...
 	 *
 	 * @return PwMedalLog
 	 */
 	private function _getMedalLogDs() {
		return app('SRV:medal.PwMedalLog');
	}
 }
?>