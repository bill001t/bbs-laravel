<?php
Wind::import('LIB:base.PwBaseController');
/**
 * the last known user to change this file in the repository  <$LastChangedBy: gao.wanggao $>
 * @author $Author: gao.wanggao $ Foxsee@aliyun.com
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: PushController.php 28899 2013-05-29 07:23:48Z gao.wanggao $ 
 * @package 
 */
class PushController extends PwBaseController{
	
	public function beforeAction($handlerAdapter) {
		parent::beforeAction($handlerAdapter);
		app('design.PwDesignPermissions');
		$permissions = $this->_getPermissionsService()->getPermissionsForUserGroup($this->loginUser->uid);
		if ($permissions < PwDesignPermissions::NEED_CHECK ) return $this->showError("DESIGN:permissions.fail");
	}
	
	public function addAction(Request $request) {
		$fromid = (int)$request->get('fromid', 'get');
		$fromtype = $request->get('fromtype', 'get');
		if (!$fromtype)  return $this->showError("operate.fail");
		$data = $this->_getPushService()->getDataByFromid($fromtype, $fromid);
		if (!$data) return $this->showError("operate.fail");
		$pageList = $this->_getPermissionsService()->getPermissionsAllPage($this->loginUser->uid);
		if (!$pageList) return $this->showError("push.page.empty");
		
		->with($pageList, 'pageList');
		
		$first = array_shift($pageList);
		$moduleList = $this->_getModuleDs()->fetchModule(explode(',', $first['module_ids']));
		foreach ($moduleList AS $k=>$module) {
			if ($module['model_flag'] != $fromtype) {
				unset($moduleList[$k]);
			}
		}
		->with($moduleList, 'moduleList');
		->with($fromtype, 'fromtype');
		->with($fromid, 'fromid');
	}
	
	public function getmoduleAction(Request $request) {
		$option = '';
		$pageid = (int)$request->get('pageid', 'post');
		$fromtype = $request->get('fromtype', 'post');
		$permissions = $this->_getPermissionsService()->getPermissionsForPage($this->loginUser->uid, $pageid);
		if ($permissions < PwDesignPermissions::NEED_CHECK ) {
			$option = '<option value="">无可用模块</option>';
			->with($option, 'data');
			return $this->showMessage("operate.success");
		}
		$moduleList = $this->_getModuleDs()->getByPageid($pageid);
		foreach ($moduleList AS $v) {
			if ($v['model_flag'] != $fromtype) continue;
			$option .= '<option value="'.$v['module_id'].'">'.$v['module_name'].'</option>';
		}
		if (!$option) $option = '<option value="">无可用模块</option>';
		->with($option, 'html');
		return $this->showMessage("operate.success");
	}
	
	
	public function doaddAction(Request $request) {
		$pageid = (int)$request->get('pageid', 'post');
		$moduleid = (int)$request->get('moduleid', 'post');
		$isnotice = (int)$request->get('isnotice', 'post');
		$fromid = (int)$request->get('fromid', 'post');
		$fromtype = $request->get('fromtype', 'post');
		$start = $request->get('start_time', 'post');
		$end = $request->get('end_time', 'post');
		if ($moduleid < 1) return $this->showError("operate.fail");
		$permiss = $this->_getPermissionsService()->getPermissionsForModule($this->loginUser->uid, $moduleid, $pageid);
		$pushService = $this->_getPushService();
		$data = $pushService->getDataByFromid($fromtype, $fromid);
		
		Wind::import('SRV:design.bo.PwDesignModuleBo');
		$bo = new PwDesignModuleBo($moduleid);
		$time = Tool::getTime();
		$startTime = $start ? Tool::str2time($start) : $time;
		$endTime = $end ? Tool::str2time($end) : $end;
		if ($end && $endTime < $time) return $this->showError("DESIGN:endtimd.error");
		$pushDs = $this->_getPushDs();
		Wind::import('SRV:design.dm.PwDesignPushDm');
 		$dm = new PwDesignPushDm();
 		$dm->setFromid($fromid)
 			->setModuleId($moduleid)
 			->setCreatedUserid($this->loginUser->uid)
 			->setCreatedTime($time)
 			->setStartTime($startTime)
 			->setEndTime($endTime)
 			->setAuthorUid($data['uid']);
 		if ($isnotice) $dm->setNeedNotice(1);
 		if ($permiss <= PwDesignPermissions::NEED_CHECK) {
 			$dm->setStatus(PwDesignPush::NEEDCHECK);
 			$isdata = false;
 		} else {
 			$isdata = true;
 		}
		$resource = $pushService->addPushData($dm);
		if ($resource instanceof ErrorBag) return $this->showError($resource->getError());
		
		if ($isdata) {
			$pushService->pushToData((int)$resource);
			$pushService->afterPush((int)$resource);
		}
		return $this->showMessage("operate.success");
	}
	
	private function _getDesignService() {
		return app('design.srv.PwDesignService');
	}

	private function _getPushService() {
		return app('design.srv.PwPushService');
	}
	
	protected function _getPermissionsService() {
		return app('design.srv.PwDesignPermissionsService');
	}
	
	private function _getModuleDs() {
		return app('design.PwDesignModule');
	}
	
	private function _getPageDs() {
		return app('design.PwDesignPage');
	}
	
	private function _getPushDs() {
		return app('design.PwDesignPush');
	}
}
?>