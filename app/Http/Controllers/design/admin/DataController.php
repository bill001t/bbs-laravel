<?php
Wind::import('APPS:design.admin.DesignBaseController');
/**
 * the last known user to change this file in the repository  <$LastChangedBy: gao.wanggao $>
 * @author $Author: gao.wanggao $ Foxsee@aliyun.com
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: DataController.php 28818 2013-05-24 10:10:46Z gao.wanggao $ 
 * @package 
 */
class DataController extends DesignBaseController {	
	
	public function run() {
		->with($this->bo->getData(), 'list');
	}
	
	public function editAction(Request $request) {
		$dataid = (int)$request->get('dataid', 'get');
		$data = $this->_getDataDs()->getData($dataid);
		if (!$data) return $this->showError("fail");
		list($data['bold'], $data['underline'], $data['italic'], $data['color']) = explode('|', $data['style']);
		$data['extend_info'] = unserialize($data['extend_info']);
		$standard = $this->_getDesignService()->getStandardSignkey($this->bo->getModel());
		$allSign = $this->_buildAllSign();
		list($threeSign, $twoSign, $oneSign) = $this->_buildModuleSign();
		foreach ($oneSign AS $k=>$sign) {
			if ($sign == $standard['sTitle']) unset($oneSign[$k]);
			if ($sign == $standard['sIntro']  && $standard['sIntro']) {
				$intro = array('name'=>$allSign[$sign], 'key'=>$standard['sIntro'], 'data'=>$data[$sign]);
				unset($oneSign[$k]);
			}
		}
		->with($intro, 'intro');
		->with($this->bo->getLimit(), 'limit');
		->with($standard['sTitle'], 'sTitle');
		->with($data, 'data');
		->with($threeSign, 'threeSign');
		->with($twoSign, 'twoSign');
		->with($oneSign, 'oneSign');
		->with($allSign, 'allSign');
	}
	
	public function doeditAction(Request $request) {
		$dataid = (int)$request->get('dataid', 'post');
		$info = $this->_getDataDs()->getData($dataid);
		if (!$info) return $this->showError("operate.fail");
		$orderid = $info['vieworder'];
		$start = $request->get('start_time', 'post');
		$end = $request->get('end_time', 'post');
		$data = $request->get('data', 'post');
		$images = $request->get('images', 'post');
		$bold = $request->get('bold', 'post');
		$italic = $request->get('italic', 'post');
		$underline = $request->get('underline', 'post');
		$color = $request->get('color', 'post');
		$standard = $this->_getDesignService()->getStandardSignkey($this->bo->getModel());
		if (!$data[$standard['sTitle']]) return $this->showError("operate.fail");
		foreach ($images AS $k=>$v) {
			if ($_FILES[$k]['name'] && $image = $this->_uploadFile($k, $this->bo->moduleid)){
				$data[$k] = $image;
			} else {
				$data[$k] = $v;
			}
		}
		
		$time = Tool::getTime();
		$startTime = $start ? Tool::str2time($start) : $time;
		$endTime = $end ? Tool::str2time($end) : $end;
		if ($end && $endTime < $time) return $this->showError("DESIGN:endtimd.error");
		Wind::import('SRV:design.dm.PwDesignDataDm');
		$dm = new PwDesignDataDm($dataid);
 		$dm->setStyle($bold, $underline, $italic, $color)
 			->setExtend($data)
 			->setStarttime($startTime)
 			->setEndtime($endTime);
		//推送的数据，不打修改标识
 		if ($info['from_type'] == PwDesignData::FROM_AUTO){	
 			$dm->setEdited(1);
		}
 		if($startTime > $time) $dm->setReservation(1);
		$this->_getDataDs()->updateData($dm);
		if ($info['from_type'] == PwDesignData::FROM_PUSH) {
			Wind::import('SRV:design.dm.PwDesignPushDm');
			$pushDm = new PwDesignPushDm($info['from_id']);
			$pushDm->setStyle($bold, $underline, $italic, $color)
 				->setExtend($data)
				->setStartTime($startTime)
				->setEndTime($endTime)
				->setModuleId($info['module_id']);
			$this->_getPushDs()->updatePush($pushDm);
		}
		return $this->showMessage("operate.success", 'design/data/run?moduleid='.$this->bo->moduleid, true);
	}
	
	public function doshieldAction(Request $request) {
		$dataid = (int)$request->get('dataid', 'get');
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
				$this->_getShieldDs()->addShield($data['from_app'], $data['from_id'], $data['module_id'], $data['title'], $data['url']);
				break;
			default:
				return $this->showError("operate.fail");
				break;
		}
		
		$extend = unserialize($data['extend_info']);
		$delImages = $extend['standard_image'];
		app('design.srv.PwDesignImage')->clearFiles($this->bo->moduleid, explode('|||', $delImages));
		
		Wind::import('SRV:design.srv.data.PwShieldData');
		$srv = new PwShieldData($data['module_id']);
		$srv->addShieldData();
		return $this->showMessage("operate.success");
	}
	
	public function pushAction(Request $request) {
		$page = (int)$request->get('page','get');
		$perpage = 10;
		$uids = array();
		$page =  $page > 1 ? $page : 1;
		$pushDs = $this->_getPushDs();
		list($start, $perpage) = Tool::page2limit($page, $perpage);
		$vo = app('design.srv.vo.PwDesignPushSo');
		$vo->setModuleid($this->bo->moduleid);
		$vo->setStatus(PwDesignPush::NEEDCHECK);
		$vo->orderbyPushid(false);
		$list = $pushDs->searchPush($vo, $perpage, $start);
		$count = $pushDs->countPush($vo);
		foreach ($list AS $k=>$v) {
			$uids[] = $v['created_userid'];
			$_tmp = unserialize($v['push_extend']);
			$standard = unserialize($v['push_standard']);
			$list[$k]['title'] = $_tmp[$standard['sTitle']];
			$list[$k]['url'] = $_tmp[$standard['sUrl']];
			$list[$k]['intro'] = $_tmp[$standard['sIntro']];
		}
		array_unique($uids);
		$users =  app('user.PwUser')->fetchUserByUid($uids);
		->with($list, 'list');
		->with($users, 'users');
		->with('moduleid='.$this->bo->moduleid, 'args');
		->with($count, 'count');
		->with($page, 'page');
		->with($perpage, 'perpage');
		->with(ceil($count/$perpage), 'totalpage');
	}
	
	
	public function dopushAction(Request $request) {
		$pushid = (int)$request->get('pushid','post');
		$pushDs = $this->_getPushDs();
		$pushDs->updateStatus($pushid, PwDesignPush::ISSHOW);
		Wind::import('SRV:design.srv.data.PwAutoData');
		$srv = new PwAutoData($this->bo->moduleid);
		$srv->addAutoData();
		return $this->showMessage("operate.success");
	}
	
	public function delpushAction(Request $request) {
		$pushid = (int)$request->get('pushid','post');
		$push = $this->_getPushDs()->getPush($pushid);
		if (!$push) return $this->showError("operate.fail");
		if($this->_getPushDs()->deletePush($pushid)) {
			$extend = unserialize($push['extend_info']);
			$delImages = $extend['standard_image'];
			app('design.srv.PwDesignImage')->clearFiles($this->bo->moduleid, explode('|||', $delImages));
			return $this->showMessage("operate.success");
		}
		return $this->showError("operate.fail");
	}
	
	public function batchEditDataAction(Request $request) {
		$dataid = $request->get('dataid','post');
		$order_tmp = $vieworder = $request->get('vieworder','post');
		$vieworder_tmp = $request->get('vieworder_tmp','post');
		$vieworder_reserv = $request->get('vieworder_reserv','post');
		$isfixed = $request->get('isfixed','post');
		Wind::import('SRV:design.dm.PwDesignDataDm');
		Wind::import('SRV:design.dm.PwDesignPushDm');
		$ds = $this->_getDataDs();
		
		//转换排序数字
		asort($vieworder);
		$i = 1;
		foreach ($vieworder AS &$order) {
			$order = $i;
			$i++;
		}
		
		foreach ($dataid AS $id) {
			$data = $ds->getData($id);
			if ($data['is_reservation']) continue;
			$dm = new PwDesignDataDm($id);
			$orderid = (int)$vieworder[$id];
			if ($isfixed[$id]) {
				$dm->setDatatype(PwDesignData::ISFIXED);
				if ($data['from_type'] == PwDesignData::FROM_PUSH) {
					$this->_getPushDs()->updateAutoByModuleAndOrder($data['module_id'], $orderid);
					$pushDm = new PwDesignPushDm($data['from_id']);
					$pushDm->setOrderid($orderid);
					$this->_getPushDs()->updatePush($pushDm);
				}
			} else {
				$dm->setDatatype(PwDesignData::AUTO);
				if ($data['from_type'] == PwDesignData::FROM_PUSH) {
					$pushDm = new PwDesignPushDm($data['from_id']);
					$pushDm->setOrderid(0);
					$this->_getPushDs()->updatePush($pushDm);
				}
			}

			$dm->setVieworder($orderid);

			//产品要求，没显性改过排序的不作编辑处理......
			if ($order_tmp[$id] != $vieworder_tmp[$id]) {
				$dm->setEdited(1);
			}
			$ds->updateData($dm);
		}
		
		//预订
		foreach ($dataid AS $id) {
			$data = $ds->getData($id);
			if (!$data['is_reservation']) continue;
			$dm = new PwDesignDataDm($id);
			$orderid = (int)$vieworder_reserv[$id];
			if ($isfixed[$id]) {
				$dm->setDatatype(PwDesignData::ISFIXED);
				$dm->setVieworder($orderid);
				if ($data['from_type'] == PwDesignData::FROM_PUSH) {
					$this->_getPushDs()->updateAutoByModuleAndOrder($data['module_id'], $orderid);
					$ds->updateFixedToAuto($data['module_id'], $orderid);
					$pushDm = new PwDesignPushDm($data['from_id']);
					$pushDm->setOrderid($orderid);
					$this->_getPushDs()->updatePush($pushDm);
				}
			} else {
				$dm->setDatatype(PwDesignData::AUTO);
				if ($data['from_type'] == PwDesignData::FROM_PUSH) {
					$pushDm = new PwDesignPushDm($data['from_id']);
					$pushDm->setOrderid(0);
					$this->_getPushDs()->updatePush($pushDm);
				}
			}
			$ds->updateData($dm);
		}
 		return $this->showMessage("operate.success");
	}
	
	public function batchCheckPushAction(Request $request) {
		$pushid = $request->get('pushid','post');
		if (!$pushid) return $this->showError("operate.fail");
		$ds = $this->_getPushDs();
		foreach ($pushid AS $id){
			$ds->updateStatus($id, PwDesignPush::ISSHOW);
		}
		Wind::import('SRV:design.srv.data.PwAutoData');
		$srv = new PwAutoData($this->bo->moduleid);
		$srv->addAutoData();
		return $this->showMessage("operate.success");
	}
	
	public function batchDelPushAction(Request $request) {
		$pushid = $request->get('pushid','post');
		if ($this->_getPushDs()->batchDelete($pushid)) return $this->showMessage("operate.success");
		return $this->showError("operate.fail");
	}
	
	
	private function _uploadFile($key, $moduleid = 0) {
 		Wind::import('SRV:upload.action.PwDesignDataUpload');
		Wind::import('LIB:upload.PwUpload');
		$bhv = new PwDesignDataUpload($key, $moduleid);
		$upload = new PwUpload($bhv);
		if (($result = $upload->check()) === true) $result = $upload->execute();
		if ($result !== true) return $this->showError($result->getError());
		$image = $bhv->getAttachInfo();
		return $image['filename'] ? Tool::getPath($image['path'] . $image['filename']) : '';
 	}
	
	/**
	 * 从全部可用模块标签中转换key=>value标签
	 * Enter description here ...
	 */
	private function _buildAllSign() {
		$signKey = $this->bo->getSignKey();
		$_key = array();
		foreach ($signKey AS $v) {
			list($_sign, $_name, $_k) = $v;
			$_name = str_replace('｜', '|', $_name);
			$_name = explode('|', $_name);
			$_name = array_shift($_name);
			if(preg_match('/\{(\w+)\|(.+)}/U', $_sign, $matches)) {
				$_key[$matches[1]] = $_name;
				continue;
			}
			if(preg_match('/\{(\w+)}/isU', $_sign, $matches)) {
				$_key[$matches[1]] = $_name;
				continue;
			}
		}
		return $_key;
	}
	
	/**
	 * 从模块模板中转换key标签
	 * Enter description here ...
	 */
	private function _buildModuleSign() {
		$tpl = $this->bo->getTemplate();
		$three = array();
		$two = array();
		$one = array();
		if(preg_match_all('/\{(\w+)\|(\d+)\|(\d+)}/U', $tpl, $matche)) {
			foreach ($matche[1] AS $k=>$v) {
				$three[] = array('sign'=>$v,'width'=>$matche[2][$k], 'height'=>$matche[3][$k]);
    		}
		}
		if(preg_match_all('/\{(\w+)\|img}/U', $tpl, $matche)) {
			foreach ($matche[1] AS $v) {
				$two[] = $v;
    		}
		}
		
		if(preg_match_all('/\{(\w+)\|(\d+)}/U', $tpl, $matche)) {
			foreach ($matche[1] AS $v) {
				$one[] =$v;
    		}
		}
		
		if(preg_match_all('/\{(\w+)}/isU', $tpl, $matche)) {
			foreach ($matche[1] AS $v) {
				$one[] = $v;
    		}
		}
		return array(array_unique($three), array_unique($two), array_unique($one));
	}

	
	private function _getPushService() {
		return app('design.srv.PwPushService');
	}
	
	
	private function _getDesignService() {
		return app('design.srv.PwDesignService');
	}
	
	private function _getPushDs() {
		return app('design.PwDesignPush');
	}
	
	private function _getDataDs() {
		return app('design.PwDesignData');
	}
	
	private function _getShieldDs() {
		return app('design.PwDesignShield');
	}
}
?>
