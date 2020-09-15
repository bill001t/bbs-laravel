<?php
Wind::import('ADMIN:library.AdminBaseController');
/**
 * the last known user to change this file in the repository  <$LastChangedBy: gao.wanggao $>
 * @author $Author: gao.wanggao $ Foxsee@aliyun.com
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: ModuleController.php 28818 2013-05-24 10:10:46Z gao.wanggao $ 
 * @package 
 */

class ModuleController extends AdminBaseController {
	
	public function run() {
		$isapi = $request->get('type');
		$ismodule = $request->get('ismodule');
		$model = $request->get('model');
		$moduleid = $request->get('moduleid');
		$modulename = $request->get('name');
		$pageid = $request->get('pageid');
		$page = (int)$request->get('page','get');
		$perpage = 10;
		$page =  $page > 1 ? $page : 1;
		list($start, $perpage) = Tool::page2limit($page, $perpage);
		$ds = $this->_getDesignModuleDs();
		Wind::import('SRV:design.srv.vo.PwDesignModuleSo');
		$vo = new PwDesignModuleSo();
		$vo->setIsUse(1);
		if ($isapi == 'api') {
			$vo->setModuleType(PwDesignModule::TYPE_SCRIPT);
			$args['type'] = 'api';
		} else {
			$vo->setModuleType(PwDesignModule::TYPE_DRAG | PwDesignModule::TYPE_IMPORT);
		}
		if ($model) {
			$vo->setModelFlag($model);
			$args['model'] = $model;
		}
		if ($moduleid > 0) {
			$vo->setModuleId($moduleid);
			$args['moduleid'] = $moduleid;
		}
		if ($modulename) {
			$vo->setModuleName($modulename);
			$args['name'] = $modulename;
		}
		
		if ($pageid) {
			$vo->setPageId($pageid);
			$args['pageid'] = $pageid;
		}
		
		$vo->orderbyModuleId(false);
		$list = $ds->searchModule($vo, $start, $perpage);
		$count = $ds->countModule($vo);
		
		Wind::import('SRV:design.bo.PwDesignModelBo');
		$pageDs = $this->_getPageDs();
		foreach ($list AS $k=>$v) {
			$list[$k]['pageInfo'] = $pageDs->getPage($v['page_id']);
			$bo = new PwDesignModelBo($v['model_flag']);
			$model = $bo->getModel();
			$list[$k]['isdata'] = true;
			if ($model['tab'] && !in_array('data', $model['tab'])) $list[$k]['isdata'] = false;
		}
		->with($this->_getDesignService()->getModelList(), 'models');
		->with($args, 'args');
		->with($list, 'list');
		->with($count, 'count');
		->with($page, 'page');
		->with($perpage, 'perpage');
		->with(ceil($count/$perpage), 'totalpage');
		->with('design/module/run', 'pageurl');
		->with($isapi, 'isapi');
		if ($isapi == 'api') {
			return view('module_api');
		}
		
	}

	public function deleteAction(Request $request) {
		$moduleid = (int)$request->get('moduleid','post');
		if ($moduleid < 1 ) return $this->showError("operate.fail");
		$this->_getDataDs()->deleteByModuleId($moduleid);
		$this->_getDesignModuleDs()->deleteModule($moduleid);
		return $this->showMessage("operate.success");
	}
	
	public function scriptAction(Request $request) {
		$moduleid = (int)$request->get('moduleid','get');
		if ($moduleid < 1 ) return $this->showError("operate.fail");
		$module = $this->_getDesignModuleDs()->getModule($moduleid);
		if ($module['module_type'] != PwDesignModule::TYPE_SCRIPT) return $this->showError("operate.fail");
		$script = $this->_getScriptDs()->getScript($moduleid);
		if (!$script)return $this->showError("operate.fail");
		$apiUrl = url('design/api/run', array('token' => $script['token'], 'id' => $moduleid), '', 'pw');
		->with('<design id="D_mod_'.$moduleid.'" role="module"></design>', 'value');
		->with($apiUrl, 'apiUrl');
		->with($module, 'module');
	}
	
	public function clearAction(Request $request) {
		Wind::import('SRV:design.srv.vo.PwDesignModuleSo');
		$vo = new PwDesignModuleSo();
		$vo->setIsUse(0);
		$list = $this->_getDesignModuleDs()->searchModule($vo, 0, 0);
		$moduleDs = $this->_getDesignModuleDs();
		$permisDs = $this->_getPermissionsDs();
		$imageSrv = app('design.srv.PwDesignImage');
		foreach ($list AS $k=>$v) {
			$permisDs->deleteByTypeAndDesignId(PwDesignPermissions::TYPE_MODULE, $k);
			$moduleDs->deleteModule($k);
			$imageSrv->clearFolder($k);
		}
		return $this->showMessage("operate.success");
	}
	
	private function _getDesignService() {
		return app('design.srv.PwDesignService');
	}
	
	private function _getDataDs() {
		return app('design.PwDesignData');
	}
	
	private function _getPageDs() {
		return app('design.PwDesignPage');
	}
	
	private function _getPushDs() {
		return app('design.PwDesignPush');
	}
	
	private function _getPermissionsDs() {
		return app('design.PwDesignPermissions');
	}
	
	private function _getDesignModuleDs() {
		return app('design.PwDesignModule');
	}
	
	private function _getScriptDs() {
		return app('design.PwDesignScript');
	}
}