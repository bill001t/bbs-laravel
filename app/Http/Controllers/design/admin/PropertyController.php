<?php

Wind::import('ADMIN:library.AdminBaseController');
Wind::import('SRV:design.bo.PwDesignModuleBo');
Wind::import('SRV:design.bo.PwDesignModelBo');
/**
 * the last known user to change this file in the repository  <$LastChangedBy: xiaoxia.xuxx $>
 * @author $Author: xiaoxia.xuxx $ Foxsee@aliyun.com
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: PropertyController.php 24134 2013-01-22 06:19:24Z xiaoxia.xuxx $ 
 * @package 
 */
class PropertyController extends AdminBaseController {
	protected $bo;
	
	public  function beforeAction($handlerAdapter) {
		parent::beforeAction($handlerAdapter);
		$isapi = '';
		$isdata = true;
		$moduleid = $request->get('moduleid');
		if ($moduleid){
			$this->bo = new PwDesignModuleBo($moduleid);
			$module = $this->bo->getModule();
			if ($module && $module['module_type'] == PwDesignModule::TYPE_SCRIPT) $isapi = 'api';
			$modelBo = new PwDesignModelBo($module['model_flag']);
			$model = $modelBo->getModel();
			if ($model['tab'] && !in_array('data', $model['tab'])) $isdata = false;
		}
		->with($moduleid, 'moduleid');
		->with($isapi, 'isapi');
		->with($isdata, 'isdata');
	}
	
	public function add1Action(Request $request) {
		->with($this->_getDesignService()->getModelList(), 'models');
	}
	
	
	public function add2Action(Request $request) {
		$model = $request->get('model','post');
		if (!$model) return $this->showError('operate.fail');
		Wind::import('SRV:design.bo.PwDesignModelBo');
		$bo = new PwDesignModelBo($model);
		if (!$bo->isModel()) return $this->showError('operate.fail');
		$cls = sprintf('PwDesign%sDataService', ucwords($model));
		Wind::import('SRV:design.srv.model.'.$model.'.'.$cls);
		$service = new $cls();
		$decorator = $service->decorateAddProperty($model);
		
		Wind::import('SRV:design.bo.PwDesignModelBo');
		$modelBo = new PwDesignModelBo($model);
		$cache['expired'] = 15;
		->with($cache, 'cache');
		->with($bo->getProperty(), 'property');
		->with($decorator, 'decorator');
		->with($model, 'model');
		->with($modelBo->getModel(), 'modelInfo');
	}
	
	public function doaddAction(Request $request) {
		$model = $request->get('model','post');
		if (!$model) return $this->showError('operate.fail');
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
		if (method_exists($service, 'decorateSaveProperty')) {
			$property = $service->decorateSaveProperty($property);
			if ($property  instanceof ErrorBag ) return $this->showError($property->getError());
		}
		
		$ds = $this->_getModuleDs();
		Wind::import('SRV:design.dm.PwDesignModuleDm');
 		$dm = new PwDesignModuleDm();
 		$dm->setFlag($model)
			->setName($name)
			->setProperty($property)
			->setCache($cache)
			->setModuleType(PwDesignModule::TYPE_SCRIPT)
			->setIsused(1);
		if ($property['html_tpl']) $dm->setModuleTpl($property['html_tpl']);
		$resource = $ds->addModule($dm);
		if ($resource instanceof ErrorBag) return $this->showError($resource->getError());
		$moduleid = (int)$resource;
	
		Wind::import('SRV:design.srv.data.PwAutoData');
		$srv = new PwAutoData($moduleid);
		$srv->addAutoData();
		//调用模块token
		$token = Utility::generateRandStr(10);
		$this->_getScriptDs()->addScript((int)$moduleid, $token, 0);
		
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
		
		if (in_array('template', $tab)) {
			return $this->showMessage("operate.success","design/template/edit?isscript=1&moduleid=".$moduleid, true);
		} else {
			return $this->showMessage("operate.success","design/module/run?type=api", true);
		}
	}
	
	public function editAction(Request $request) {
		$isedit = false;
		$model = $request->get('model', 'get');//前台为post
		if ($model){
			$isedit = true;
			$this->bo->setModel($model);
		} else {
			$model = $this->bo->getModel();
		}
		$module = $this->bo->getModule();
		if (!$model) return $this->showError('operate.fail');
		$cls = sprintf('PwDesign%sDataService', ucwords($model));
		Wind::import('SRV:design.srv.model.'.$model.'.'.$cls);
		$service = new $cls();
		$decorator = $service->decorateEditProperty($this->bo);
		Wind::import('SRV:design.bo.PwDesignModelBo');
		$modelBo = new PwDesignModelBo($model);
		$property = $modelBo->getProperty();
		$vProperty = $isedit ? array() : $this->bo->getProperty();
		$isedit && $vProperty['compid'] = null;
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
		->with($this->bo->getCache(), 'cache');
		->with($modelBo->getModel(), 'modelInfo');
		->with($isedit, 'isedit');
	}
	
	public function doeditAction(Request $request) {
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
		if (!$module) return $this->showError('operate.fail');
		$name = trim($request->get('module_name','post'));
		if (empty($name)) return $this->showError('DESIGN:module.name.empty');
		$cache = $request->get('cache','post');
		$property = $request->get('property','post');
		if ($property['limit'] > 200) return $this->showError('DESIGN:maxlimit.error');
		$cls = sprintf('PwDesign%sDataService', ucwords($model));
		Wind::import('SRV:design.srv.model.'.$model.'.'.$cls);
		$service = new $cls();
		if (method_exists($service, 'decorateSaveProperty')) {
			$property = $service->decorateSaveProperty($property);
			if ($property  instanceof ErrorBag ) return $this->showError($property->getError());
		}
		Wind::import('SRV:design.dm.PwDesignModuleDm');
 		$dm = new PwDesignModuleDm($moduleid);
 		$dm->setFlag($model)
			->setName($name)
			->setProperty($property)
			->setCache($cache);
		if ($property['html_tpl'])$dm->setModuleTpl($property['html_tpl']);
		$resource = $this->_getModuleDs()->updateModule($dm);
		if ($resource instanceof ErrorBag) return $this->showError($resource->getError());
		
		app('design.srv.PwSegmentService')->updateSegmentByPageId($module['page_id']);
		$this->_getDesignService()->clearCompile();
		Wind::import('SRV:design.srv.data.PwAutoData');
		$srv = new PwAutoData($moduleid);
		$srv->addAutoData();
		return $this->showMessage("operate.success");
	}
	
	private function _getCompileService() {
		return app('design.srv.PwDesignCompile');
	}
	
	private function _getDesignService() {
		return app('design.srv.PwDesignService');
	}
	
	private function _getComponentDs() {
		return app('design.PwDesignComponent');
	}
	
	private function _getShieldDs() {
		return app('design.PwDesignShield');
	}
	
	private function _getPageDs() {
		return app('design.PwDesignPage');
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
	
	private function _getSegmentDs() {
		return app('design.PwDesignSegment');
	}
	
	private function _getScriptDs() {
		return app('design.PwDesignScript');
	}
	
}
?>