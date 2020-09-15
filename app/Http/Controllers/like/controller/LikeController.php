<?php
Wind::import('LIB:base.PwBaseController');

/**
 * the last known user to change this file in the repository  <$LastChangedBy: jinlong.panjl $>
 * @author $Author: jinlong.panjl $ foxsee@aliyun.com
 * @copyright ?2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: LikeController.php 6265 2012-03-20 01:15:06Z jinlong.panjl $ 
 * @package 
 */

class LikeController extends Controller{
	
	public function run() {
		//seo设置
		Wind::import('SRV:seo.bo.PwSeoBo');
		$seoBo = PwSeoBo::getInstance();
		$seoBo->init('like', 'hot');
		Core::setV('seo', $seoBo);
	}
	public function dataAction(Request $request) {
		$cron = false;
		$_data = array();
		$page = (int)$request->get('page','get');
		$pageid = (int)$request->get('pageid','get');
		$moduleid = (int)$request->get('moduleid','get');
		$start = (int)$request->get('start','get');
		$start >= 100 && $start = 100;
		$module = app('design.PwDesignModule')->getModule($moduleid);
		$perpage = 20;
		if (!$module) return $this->showMessage("operate.fail"); //返回成功信息
		$time = Tool::getTime();
		Wind::import('SRV:design.bo.PwDesignPageBo');
    	$pageBo = new PwDesignPageBo();
		$ds = app('design.PwDesignData');
		$vo = app('design.srv.vo.PwDesignDataSo');
		$vo->setModuleId($moduleid);
		$vo->setReservation(0);
		$vo->orderbyViewOrder(true);
		$vo->orderbyDataid(true);
		$data = $ds->searchData($vo, $perpage, $start);
		$this->_getLikeContentService();
		foreach ($data AS $k=>$v) {
    		$_data[$k] = unserialize($v['extend_info']);
    		$_data[$k]['fromtype'] = ($v['from_app'] == 'thread') ? PwLikeContent::THREAD : 0 ;
    		$_data[$k]['fromid'] = $v['from_id'];
			if ($v['end_time'] > 0 && $v['end_time'] < $time){
    			$cron = true;
    		}
			foreach ($_data[$k] AS $_k=>$_v) {
				$_data[$k][$_k] = Security::escapeHTML($_v);
			}
    	}
    	if ($cron || count($data) < 1) {
    		$pageBo->updateDesignCron(array($moduleid));
    	}
    	->with($_data, 'html');
    	return $this->showMessage('operate.success');
	}

	
	public function getLastAction(Request $request) {
		$fromid = (int)$request->get('fromid','get');
		$typeid = (int)$request->get('typeid','get');
		$_users = array();
		$like = $this->_getLikeContentService()->getInfoByTypeidFromid($typeid, $fromid);
		!$like && return $this->showError('BBS:like.fail');
		$uids = $like['users'] ? explode(',', $like['users']) : array();
		$userInfos = app('user.PwUser')->fetchUserByUid($uids);
		foreach ($userInfos AS $user) {
			if (!$user['uid']) continue;
			$_users[$user['uid']]['uid'] = $user['uid'];
			$_users[$user['uid']]['username'] = $user['username'];
			$_users[$user['uid']]['avatar'] = Tool::getAvatar($user['uid']);
		}
		->with($_users, 'data');
		return $this->showMessage('BBS:like.success');
	}
	
	private function _getLikeContentService() {
		return app('like.PwLikeContent');
	}
	
}