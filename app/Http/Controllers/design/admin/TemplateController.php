<?php
Wind::import('APPS:design.admin.DesignBaseController');
/**
 * the last known user to change this file in the repository  <$LastChangedBy: gao.wanggao $>
 * @author $Author: gao.wanggao $ Foxsee@aliyun.com
 * @copyright ?2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: TemplateController.php 28936 2013-05-31 02:50:17Z gao.wanggao $ 
 * @package 
 */
class TemplateController extends DesignBaseController{

	public function editAction(Request $request) {
		
		$module = $this->bo->getModule();
		$compid = '';
		$components = $this->_getComponentDs()->getComponentByFlag($this->bo->getModel());
		$tpl = $this->bo->getTemplate();
		->with($components, 'components');
		if ($tpl == '') {
			$comp = array_shift($components);
			if ($comp) {
				$compid = $comp['comp_id'];
				$tpl  = $comp['comp_tpl'];
			}
		} else {
			$compid = $module['module_compid']; 
		}
		->with($this->bo->getSignKey(), 'signkeys');
		->with($compid, 'compid');
		->with($tpl, 'tpl');
		->with($request->get('isscript','get'), 'isscript');
	}
	
	public function getcompAction(Request $request) {
		$compid = (int)$request->get('compid','post');
		$component = $this->_getComponentDs()->getComponent($compid);
		if ($compid) {
			$tpl = $component['comp_tpl'];
		} else {
			$tpl = $this->bo->getTemplate();
		}
		->with($tpl, 'html');
		return $this->showMessage("operate.success");
	}
	
	public function doeditAction(Request $request) {
		$tpl = $request->get('tpl','post');
		$compid = (int)$request->get('compid','post');
		$tpl = $this->_getDesignService()->filterTemplate($tpl);
		if (!$this->_getDesignService()->checkTemplate($tpl)) return $this->showError("DESIGN:template.error");
		$property = $this->bo->getProperty();
		$limit = $this->compileFor($tpl);
		$property['limit'] = $limit ? $limit : $property['limit'];
		Wind::import('SRV:design.dm.PwDesignModuleDm');
 		$dm = new PwDesignModuleDm($this->bo->moduleid);
 		$dm->setModuleTpl($tpl)
 			->setCompid($compid)
 			->setProperty($property);
 		$resource = $this->_getModuleDs()->updateModule($dm);
		if ($resource instanceof ErrorBag) return $this->showError($resource->getError());
		
		$module = $this->bo->getModule();
		app('design.srv.PwSegmentService')->updateSegmentByPageId($module['page_id']);
		Wind::import('SRV:design.srv.data.PwAutoData');
		$srv = new PwAutoData($this->bo->moduleid);
		$srv->addAutoData();
		$this->_getDesignService()->clearCompile();
		if ($module['module_type'] == PwDesignModule::TYPE_SCRIPT) {
			return $this->showMessage("operate.success","design/module/run?type=api", true);
		} else {
			return $this->showMessage("operate.success");
		}
	}
	
	public function dosaveAction(Request $request) {
		$tplname = $request->get('tplname','post');
		$tpl = $request->get('tpl','post');
		$tpl = $this->_getDesignService()->filterTemplate($tpl);
		if (!$this->_getDesignService()->checkTemplate($tpl)) return $this->showError("DESIGN:template.error");
		$return = $this->_getComponentDs()->addComponent($this->bo->getModel(), $tplname, $tpl);
		if ($return) return $this->showMessage("operate.success");
		return $this->showError("operate.fail");
	}
	
	/**
	 * 对<for:1>进行解析
	 * Enter description here ...
	 */
	protected function compileFor($section) {
		$limit = 0;
		if(preg_match('/\<for:(\d+)>/isU', $section, $matches)) {
			$limit = (int)$matches[1];
		}
		return $limit;
	}
	
	private function _getDesignService() {
		return app('design.srv.PwDesignService');
	}
	
	private function _getCompileService() {
		return app('design.srv.PwDesignCompile');
	}
	
	private function _getPageDs() {
		return app('design.PwDesignPage');
	}
	
	private function _getSegmentDs() {
		return app('design.PwDesignSegment');
	}
	
	private function _getPushDs() {
		return app('design.PwDesignPush');
	}
	
	private function _getDataDs() {
		return app('design.PwDesignData');
	}
	
	private function _getModuleDs() {
		return app('design.PwDesignModule');
	}
	
	public function _getComponentDs(){
		return app('design.PwDesignComponent');
	}
}
?>