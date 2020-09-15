<?php
Wind::import('LIB:base.PwBaseController');
/**
 * the last known user to change this file in the repository  <$LastChangedBy: gao.wanggao $>
 * @author $Author: gao.wanggao $ Foxsee@aliyun.com
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: PropertyController.php 24726 2013-02-18 06:15:04Z gao.wanggao $ 
 * @package 
 */
class PropertyController extends PwBaseController{

	public function addAction(Request $request) {
		$struct = $request->get('struct','post');
		$model = $request->get('model','post');
		$pageid = $request->get('pageid','post');
		if (!$model) return $this->showError('operate.fail');
		app('design.PwDesignPermissions');
		$permissions = $this->_getPermissionsService()->getPermissionsForPage($this->loginUser->uid, $pageid);
		if ($permissions < PwDesignPermissions::IS_DESIGN ) return $this->showError("DESIGN:permissions.fail");
		Wind::import('SRV:design.bo.PwDesignModelBo');
		$bo = new PwDesignModelBo($model);
		if (!$bo->isModel()) return $this->showError('operate.fail');
		$cls = sprintf('PwDesign%sDataService', ucwords($model));
		Wind::import('SRV:design.srv.model.'.$model.'.'.$cls);
		$service = new $cls();
		$decorator = $service->decorateAddProperty($model);
		$_models = array();
		$service = $this->_getDesignService();
		$types = $service->getDesignModelType();
		$models = $service->getModelList();
		foreach ($models AS $k=>$v) {
			$_models[$v['type']][] = array('name'=>$v['name'], 'model'=>$k);
		}
		$ds = $this->_getModuleDs();
		$pageInfo = $this->_getPageDs()->getPage($pageid);
		$module['module_name'] = $pageInfo['page_name'] . '_' . Utility::generateRandStr(4);
		$cache['expired'] = 15;
		->with($cache, 'cache');
		->with($module, 'module');
		->with($types, 'types');
		->with($_models, 'models');
		->with($bo->getProperty(), 'property');
		->with($bo->getModel(), 'modelInfo');
		->with($decorator, 'decorator');
		->with($model, 'model');
		->with($pageid, 'pageid');
		->with($struct, 'struct');
	}
	
	public function doaddAction(Request $request) {
		$struct = $request->get('struct','post');
		$pageid = $request->get('pageid','post');
		$model = $request->get('model','post');
		if (!$model || $pageid <1) return $this->showError('operate.fail');
		app('design.PwDesignPermissions');
		$permissions = $this->_getPermissionsService()->getPermissionsForPage($this->loginUser->uid, $pageid);
		if ($permissions < PwDesignPermissions::IS_DESIGN ) return $this->showError("DESIGN:permissions.fail");
		Wind::import('SRV:design.bo.PwDesignModelBo');
		$bo = new PwDesignModelBo($model);
		if (!$bo->isModel()) return $this->showError('operate.fail');
		$name = trim($request->get('module_name','post'));
		if (empty($name)) return $this->showError('DESIGN:module.name.empty');
		$cache = $request->get('cache','post');
		$property = $request->get('property','post');
		if ($property['limit'] > 200) return $this->showError('DESIGN:maxlimit.error');
		$cls = sprintf('PwDesign%sDataService', ucwords($model));
		Wind::import('SRV:design.srv.model.'.$model.'.'.$cls);
		$service = new $cls();
		
		
		$ds = $this->_getModuleDs();
		Wind::import('SRV:design.dm.PwDesignModuleDm');
 		$dm = new PwDesignModuleDm();
 		$dm->setPageId($pageid)
 			->setStruct($struct)
 			->setFlag($model)
			->setName($name)
			->setCache($cache)
			->setModuleType(PwDesignModule::TYPE_DRAG)
			->setIsused(1);
		$resource = $ds->addModule($dm);
		if ($resource instanceof ErrorBag) return $this->showError($resource->getError());
		
		$dm = new PwDesignModuleDm($resource);
		if (method_exists($service, 'decorateSaveProperty')) {
			$property = $service->decorateSaveProperty($property, $resource);
			if ($property  instanceof ErrorBag ) return $this->showError($property->getError());
		}
		$dm->setProperty($property);
		if ($property['html_tpl']) $dm->setModuleTpl($property['html_tpl']);
		$r = $ds->updateModule($dm);
		if ($r instanceof ErrorBag) return $this->showError($r->getError());
		
		Wind::import('SRV:design.srv.data.PwAutoData');
		$srv = new PwAutoData($resource);
		$srv->addAutoData();
		
		->with($resource, 'data');
		return $this->showMessage("operate.success");
	}
	
	public function editAction(Request $request) {
		$other = array('html', 'searchbar', 'image');
		$isedit = false;
		$model = $request->get('model', 'post');
		$moduleid = (int)$request->get('moduleid', 'post');
		Wind::import('SRV:design.bo.PwDesignModuleBo');
		$moduleBo = new PwDesignModuleBo($moduleid);
		if ($model){
			$isedit = true;
			$moduleBo->setModel($model);
		} else {
			$model = $moduleBo->getModel();
		}
		$module = $moduleBo->getModule();
		if (!$model) return $this->showError('operate.fail');
		app('design.PwDesignPermissions');
		$permissions = $this->_getPermissionsService()->getPermissionsForModule($this->loginUser->uid,$moduleid, $module['page_id']);
		if ($permissions < PwDesignPermissions::IS_ADMIN && !in_array($module['model_flag'], $other)) return $this->showError("DESIGN:permissions.fail");
		if ($permissions < PwDesignPermissions::IS_PUSH) return $this->showError("DESIGN:permissions.fail");
		$cls = sprintf('PwDesign%sDataService', ucwords($model));
		Wind::import('SRV:design.srv.model.'.$model.'.'.$cls);
		$service = new $cls();
		$decorator = $service->decorateEditProperty($moduleBo);
		Wind::import('SRV:design.bo.PwDesignModelBo');
		$modelBo = new PwDesignModelBo($model);
		$property = $modelBo->getProperty();
		$vProperty = $isedit ? array() : $moduleBo->getProperty();
		//$isedit && $vProperty['compid'] = null;
		$service = $this->_getDesignService();
		$types = $service->getDesignModelType();
		$models = $service->getModelList();
		foreach ($models AS $k=>$v) {
			$_models[$v['type']][] = array('name'=>$v['name'], 'model'=>$k);
		}
		->with($types, 'types');
		->with($_models, 'models');
		->with($model, 'model');
		->with($modelBo->getProperty(), 'property');
		->with($decorator, 'decorator');
		->with($module, 'module');
		->with($vProperty, 'vProperty');
		->with($moduleBo->getCache(), 'cache');
		->with($modelBo->getModel(), 'modelInfo');
		->with($isedit, 'isedit');
		
	}
	
	public function doeditAction(Request $request) {
		$other = array('html', 'searchbar', 'image');
		$model = $request->get('model', 'post');
		$moduleid = $request->get('moduleid','post');
		if (!$moduleid) return $this->showError('operate.fail');
		Wind::import('SRV:design.bo.PwDesignModuleBo');
		$moduleBo = new PwDesignModuleBo($moduleid);
		$_model = $moduleBo->getModel();
		if ($model != $_model) {
			$this->_getDataDs()->deleteByModuleId($moduleid);
			$this->_getPushDs()->deleteByModuleId($moduleid);
		}
		!$model && $model = $_model;
		$module = $moduleBo->getModule();
		if (!$module || $module['page_id'] < 1) return $this->showError('operate.fail');
		
		Wind::import('SRV:design.bo.PwDesignPageBo');
		$pageBo = new PwDesignPageBo($module['page_id']);
		if ($pageBo->getLock()) return $this->showError('DESIGN:page.edit.other.user');
		
		app('design.PwDesignPermissions');
		$permissions = $this->_getPermissionsService()->getPermissionsForModule($this->loginUser->uid,$moduleid, $module['page_id']);
		if ($permissions < PwDesignPermissions::IS_ADMIN && !in_array($module['model_flag'], $other)) return $this->showError("DESIGN:permissions.fail");
		if ($permissions < PwDesignPermissions::IS_PUSH) return $this->showError("DESIGN:permissions.fail");
		$name = trim($request->get('module_name','post'));
		if (empty($name)) return $this->showError('DESIGN:module.name.empty');
		$cache = $request->get('cache','post');
        $property = $request->get('property','post');
        $property['html'] = $this->_getDesignService()->filterTemplate($property['html']);
        if (!$this->_getDesignService()->checkTemplate($property['html'])) return $this->showError("DESIGN:template.error");
        //
		if ($property['limit'] > 200) return $this->showError('DESIGN:maxlimit.error');
		$cls = sprintf('PwDesign%sDataService', ucwords($model));
		Wind::import('SRV:design.srv.model.'.$model.'.'.$cls);
		$service = new $cls();
		if (method_exists($service, 'decorateSaveProperty')) {
			$property = $service->decorateSaveProperty($property, $moduleid);
			if ($property  instanceof ErrorBag ) return $this->showError($property->getError());
		}
		Wind::import('SRV:design.dm.PwDesignModuleDm');
 		$dm = new PwDesignModuleDm($moduleid);
 		$dm->setFlag($model)
			->setName($name)
			->setProperty($property)
			->setCache($cache);
		if (isset($property['html_tpl']))$dm->setModuleTpl($property['html_tpl']);
		$resource = $this->_getModuleDs()->updateModule($dm);
		if ($resource instanceof ErrorBag) return $this->showError($resource->getError());
		
		Wind::import('SRV:design.srv.data.PwAutoData');
		$srv = new PwAutoData($moduleid);
		$srv->addAutoData();
		return $this->showMessage("operate.success");
	}
	
	/**
	 * 对模块进行删除
	 * PS:不是真正的删除，记录为isused = 0状态
	 */
	public function deleteAction(Request $request) {
		$moduleid = (int)$request->get('moduleid', 'post');
		$module = $this->_getModuleDs()->getModule($moduleid);
		if (!$module || $module['page_id'] < 1) return $this->showError('operate.fail');
		
		app('design.PwDesignPermissions');
		$permissions = $this->_getPermissionsService()->getPermissionsForModule($this->loginUser->uid,$moduleid);
		if ($permissions < PwDesignPermissions::IS_DESIGN ) return $this->showError("DESIGN:permissions.fail");
		Wind::import('SRV:design.bo.PwDesignPageBo');
		$pageBo = new PwDesignPageBo($module['page_id']);
		if ($pageBo->getLock()) return $this->showError('DESIGN:page.edit.other.user');
		Wind::import('SRV:design.dm.PwDesignModuleDm');
 		$dm = new PwDesignModuleDm($moduleid);
 		$dm->setIsused(0);
 		$resource = $this->_getModuleDs()->updateModule($dm);
 		if ($resource instanceof ErrorBag) return $this->showError($resource->getError());
		//if (!$this->_getModuleDs()->deleteModule($moduleid)) return $this->showMessage("operate.fail");
		$this->_getDataDs()->deleteByModuleId($moduleid);
		app('design.PwDesignPush')->deleteByModuleId($moduleid);

		//删除导入数据的模版内容
		$dir = Wind::getRealDir('THEMES:portal.local.');
		$path = $dir .$pageBo->getTplPath() . '/template/';
		$files = WindFolder::read($path, WindFolder::READ_FILE);
		foreach ($files AS $file) {
			$filePath = $path . $file;
			$content = WindFile::read($filePath);
			if (!$content) continue;
			$tmp = preg_replace('/\<pw-list\s*id=\"'.$moduleid.'\"\s*>(.+)<\/pw-list>/isU','', $content);
			if ($tmp != $content) WindFile::write($filePath, $tmp);
		}
		$imageSrv = app('design.srv.PwDesignImage');
		$imageSrv->clearFolder($moduleid);
		return $this->showMessage("operate.success");
	}
	
	public function gettabAction(Request $request) {
		$model = $request->get('model','post');
		$pageid = $request->get('pageid','post');
		if (!$model) return $this->showError('operate.fail');
		app('design.PwDesignPermissions');
		$permissions = $this->_getPermissionsService()->getPermissionsForPage($this->loginUser->uid, $pageid);
		if ($permissions < PwDesignPermissions::IS_DESIGN ) return $this->showError("DESIGN:permissions.fail");
		
		//对config里的tab进行过滤
		$tab = array('property','template');
		Wind::import('SRV:design.bo.PwDesignModelBo');
		$bo = new PwDesignModelBo($model);
		$modelInfo = $bo->getModel();
		if (is_array($modelInfo['tab'])) {
			foreach ($tab AS $k=>$v) {
				if (in_array($v, $modelInfo['tab'])) $_tab[] = $tab[$k];
			}
			$tab = $_tab;
		}
		->with($tab, 'data');
		return $this->showMessage("operate.success");
	}
	
	protected function _getPermissionsService() {
		return app('design.srv.PwDesignPermissionsService');
	}
	
	private function _getDesignService() {
		return app('design.srv.PwDesignService');
	}
	
	private function _getComponentDs() {
		return app('design.PwDesignComponent');
	}
	
	private function _getPageDs() {
		return app('design.PwDesignPage');
	}
	
	private function _getShieldDs() {
		return app('design.PwDesignShield');
	}
	
	private function _getDataDs() {
		return app('design.PwDesignData');
	}
	
	private function _getPushDs() {
		return app('design.PwDesignPush');
	}
	
	private function _getModelDs() {
		return app('design.PwDesignModel');
	}
	
	private function _getModuleDs() {
		return app('design.PwDesignModule');
	}
	
	private function _getBakDs() {
		return app('design.PwDesignBak');
	}
}
?>
