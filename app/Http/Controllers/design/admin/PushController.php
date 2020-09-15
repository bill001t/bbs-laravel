<?php
Wind::import('ADMIN:library.AdminBaseController');
/**
 * the last known user to change this file in the repository  <$LastChangedBy: gao.wanggao $>
 * @author $Author: gao.wanggao $ Foxsee@aliyun.com
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: PushController.php 28818 2013-05-24 10:10:46Z gao.wanggao $ 
 * @package 
 */

class PushController extends AdminBaseController {
	
	public function run() {
		$page = (int)$request->get('page','get');
		$moduleid = (int)$request->get('moduleid');
		$pageid = (int)$request->get('pageid');
		$perpage = 10;
		$pushids = $pageids = $moduleids = $uids = $args = array();
		$page =  $page > 1 ? $page : 1;
		list($start, $perpage) = Tool::page2limit($page, $perpage);
		if ($pageid) $args = array('pageid'=>$pageid);
		if ($moduleid) $args['moduleid']=$moduleid;
		if ($pageid && !$moduleid) {
			$pageinfo = $this->_getPageDs()->getPage($pageid);
			$moduleid = explode(',', $pageinfo['module_ids']);
			
		}
		$ds = $this->_getDataDs();
		Wind::import('SRV:design.srv.vo.PwDesignDataSo');
		$vo = new PwDesignDataSo();
		if ($moduleid) {
			$vo->setModuleid($moduleid);
		}
		$vo->setFromType(PwDesignData::FROM_PUSH);
		$list = $ds->searchData($vo, $perpage, $start);
		$count = $ds->countData($vo);
		$pagelist = $this->_getPageDs()->getPageList();
		foreach ($list AS $k=>$v) {
			$moduleids[] = $v['module_id'];
			$pushids[] = $v['from_id'];
			$_tmp = unserialize($v['extend_info']);
			$standard = unserialize($v['standard']);
			$list[$k]['title'] = $_tmp[$standard['sTitle']];
			$list[$k]['url'] = $_tmp[$standard['sUrl']];
			$list[$k]['intro'] = $_tmp[$standard['sIntro']];
		}
		array_unique($moduleids);
		$modules =  $this->_getModuleDs()->fetchModule($moduleids);
		$pushs = $this->_getPushDs()->fetchPush($pushids);
		foreach($pushs AS $v) {
			$uids[] = $v['created_userid'];
		}
		$users =  app('user.PwUser')->fetchUserByUid($uids);
		foreach($pushs AS &$push) {
			$push['created_user'] = $users[$push['created_userid']]['username'];
		}
		->with($moduleid, 'moduleid');
		->with($pageid, 'pageid');
		->with($modules, 'modules');
		->with($pagelist, 'pagelist');
		->with($pushs, 'pushs');
		->with($list, 'list');
		->with($args, 'args');
		->with($count, 'count');
		->with($page, 'page');
		->with($perpage, 'perpage');
		->with(ceil($count/$perpage), 'totalpage');
	}
	
	public function statusAction(Request $request) {
		$status = (int)$request->get('status','get');
		$page = (int)$request->get('page','get');
		$moduleid = (int)$request->get('moduleid');
		$pageid = (int)$request->get('pageid');
		$perpage = 10;
		$pageids = $moduleids = $uids = array();
		$page =  $page > 1 ? $page : 1;
		$args['status'] = $status ;
		if ($moduleid)$args['moduleid'] = $moduleid;
		if ($pageid && !$moduleid) {
			$pageinfo = $this->_getPageDs()->getPage($pageid);
			$moduleid = explode(',', $pageinfo['module_ids']);
			$args['pageid'] =$pageid;
		}
		list($start, $perpage) = Tool::page2limit($page, $perpage);
		$time = Tool::getTime();
		$ds = $this->_getPushDs();
		$vo = app('design.srv.vo.PwDesignPushSo');
		$moduleid && $vo->setModuleid($moduleid);
		if ($status == 1)$vo->setStatus(1);
		if ($status == 2)$vo->setStatus(0);
		$vo->orderbyPushid(false);
		$list = $ds->searchPush($vo, $perpage, $start);
		$count = $ds->countPush($vo);

		foreach ($list AS $k=>$v) {
			$uids[] = $v['created_userid'];
			$moduleids[] = $v['module_id'];
			$_tmp = unserialize($v['push_extend']);
			$standard = unserialize($v['push_standard']);
			$list[$k]['title'] = $_tmp[$standard['sTitle']];
			$list[$k]['url'] = $_tmp[$standard['sUrl']];
			$list[$k]['intro'] = $_tmp[$standard['sIntro']];
		}
		array_unique($uids);
		array_unique($moduleids);
		$modules =  $this->_getModuleDs()->fetchModule($moduleids);
		
		$pagelist = $this->_getPageDs()->getPageList();
		$users =  app('user.PwUser')->fetchUserByUid($uids);
		->with($moduleid, 'moduleid');
		->with($pageid, 'pageid');
		->with($pagelist, 'pagelist');
		->with($list, 'list');
		->with($users, 'users');
		->with($modules, 'modules');
		->with($args, 'args');
		->with($count, 'count');
		->with($page, 'page');
		->with($perpage, 'perpage');
		->with(ceil($count/$perpage), 'totalpage');
		->with($status, 'status');
	}
	
	public function shieldAction(Request $request) {
		$page = (int)$request->get('page','get');
		$perpage = 10;
		$page =  $page > 1 ? $page : 1;
		list($start, $perpage) = Tool::page2limit($page, $perpage);
		$count =  $this->_getShieldDs()->countShield(0);
		$list = $this->_getShieldDs()->getShieldList(0,$start, $perpage);
		->with($list, 'list');
		->with('', 'args');
		->with($count, 'count');
		->with($page, 'page');
		->with($perpage, 'perpage');
		->with(ceil($count/$perpage), 'totalpage');
	}
	
	public function doshieldAction(Request $request) {
		$dataid = (int)$request->get('dataid', 'post');
		$ds = $this->_getDataDs();
		$data = $ds->getData($dataid);
		if (!$data) return $this->showError("operate.fail");
		
		switch ($data['from_type']) {
			case PwDesignData::FROM_PUSH:  
				$resource = $ds->deleteData($dataid);
				$this->_getPushDs()->deletePush($data['from_id']);
				//$this->_getPushDs()->updateStatus($data['from_id'], PwDesignPush::ISSHIELD);
				break;
			case PwDesignData::FROM_AUTO:  
				$resource = $ds->deleteData($dataid);
				$this->_getShieldDs()->addShield($data['from_app'], $data['from_id'], $data['module_id']);
				break;
			default:
				return $this->showError("operate.fail");
				break;
		}
		$extend = unserialize($data['extend_info']);
		$delImages = $extend['standard_image'];
		app('design.srv.PwDesignImage')->clearFiles($this->bo->moduleid, explode('|||', $delImages));
		if (!$data['is_reservation']) {
			Wind::import('SRV:design.srv.data.PwShieldData');
			$srv = new PwShieldData($data['module_id']);
			$srv->addShieldData();
		}
		return $this->showMessage("operate.success");
	}
	
	public function doshielddeleteAction(Request $request) {
		$shieldid = (int)$request->get('shieldid', 'post');
		if ($this->_getShieldDs()->deleteShield($shieldid)) {
			return $this->showMessage("operate.success");
		} else {
			return $this->showError("operate.fail");
		}
	}
	
	public function doshielddeletesAction(Request $request) {
		$shieldids = $request->get('shieldids', 'post');
		foreach ((array)$shieldids AS $shieldid) {
			$this->_getShieldDs()->deleteShield($shieldid);
		}
		return $this->showMessage("operate.success");
	}
	
	public function dopushAction(Request $request) {
		$pushid = (int)$request->get('pushid','get');
		$pushDs = $this->_getPushDs();
		$push = $pushDs->getPush($pushid);
		$pushDs->updateStatus($pushid, PwDesignPush::ISSHOW);
		Wind::import('SRV:design.srv.data.PwAutoData');
		$srv = new PwAutoData($push['module_id']);
		$srv->addAutoData();
		return $this->showMessage("operate.success");
	}
	
	public function delpushAction(Request $request) {
		$pushid = (int)$request->get('pushid','get');
		if (!$pushid) return $this->showError("operate.fail");
		$ds = $this->_getDataDs();
		$pushDs = $this->_getPushDs();
		$push = $pushDs->getPush($pushid);
		//TODO 权限
		if ($this->_getPushDs()->deletePush($pushid)) {
			Wind::import('SRV:design.srv.vo.PwDesignDataSo');
			$vo = new PwDesignDataSo();
			$vo->setModuleid($push['module_id']);
			$vo->setFromType(PwDesignData::FROM_PUSH);
			$vo->setFromid($pushid);
			$list = $ds->searchData($vo, 1, 0);
			if ($list) {
				$data = array_shift($list);
				$extend = unserialize($data['extend_info']);
				$delImages = $extend['standard_image'];
				app('design.srv.PwDesignImage')->clearFiles($push['module_id'], explode('|||', $delImages));
			}
			return $this->showMessage("operate.success");
		}
		return $this->showError("operate.fail");
	}
	
	public function batchshieldAction(Request $request) {
		$dataids = $request->get('dataids','post');
		$ds = $this->_getDataDs();
		Wind::import('SRV:design.srv.data.PwShieldData');
		foreach ($dataids AS $dataid) {
			$data = $ds->getData($dataid);
			if (!$data) continue;
			
			switch ($data['from_type']) {
				case PwDesignData::FROM_PUSH:  
					$resource = $ds->deleteData($dataid);
					//$this->_getPushDs()->updateStatus($data['from_id'], PwDesignPush::ISSHIELD);
					$this->_getPushDs()->deletePush($data['from_id']);
					break;
				case PwDesignData::FROM_AUTO:  
					$resource = $ds->deleteData($dataid);
					$this->_getShieldDs()->addShield($data['from_app'], $data['from_id'], $data['module_id']);
					break;
				default:
					return $this->showError("operate.fail");
					break;
			}
			$srv = new PwShieldData($data['module_id']);
			$srv->addShieldData();
		}
		return $this->showMessage("operate.success");
	}
	
	
	public function batchcheckAction(Request $request) {
		$moduleids = array();
		$pushids = $request->get('pushids','post');
		$pushDs = $this->_getPushDs();
		$srv = $this->_getPushService();
		foreach ($pushids AS $pushid) {
			$pushInfo = $pushDs->getPush($pushid);
			$pushDs->updateStatus($pushid, PwDesignPush::ISSHOW);
			$moduleids[] = $pushInfo['module_id'];
		}
		$moduleids = array_unique($moduleids);
		Wind::import('SRV:design.srv.data.PwAutoData');
		foreach ($moduleids AS $moduleid) {	
			$srv = new PwAutoData($moduleid);
			$srv->addAutoData();
		}
		//多模块不允许更新
		return $this->showMessage("operate.success");
	}
	
	public function batchdeleteAction(Request $request) {
		$pushids = $request->get('pushids','post');
		if ($this->_getPushDs()->batchDelete($pushids)) return $this->showMessage("operate.success");
		return $this->showMessage("operate.fail");
	}
	
	private function _getPushService() {
		return app('design.srv.PwPushService');
	}
	
	private function _getPushDs() {
		return app('design.PwDesignPush');
	}
	
	private function _getDataDs() {
		return app('design.PwDesignData');
	}
	
	private function _getPageDs() {
		return app('design.PwDesignPage');
	}
	
	private function _getModuleDs() {
		return app('design.PwDesignModule');
	}
	
	private function _getShieldDs() {
		return app('design.PwDesignShield');
	}
}