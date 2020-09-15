<?php
Wind::import('LIB:base.PwBaseController');
/**
 * the last known user to change this file in the repository  <$LastChangedBy: gao.wanggao $>
 * @author $Author: gao.wanggao $ Foxsee@aliyun.com
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: DesignController.php 29033 2013-06-05 02:56:40Z gao.wanggao $ 
 * @package 
 */
class DesignController extends Controller{
	
	public  function beforeAction($handlerAdapter) {
		parent::beforeAction($handlerAdapter);
	}

	public function moduleAction(Request $request) {
		$moduleId = (int)$request->get('moduleid', 'post');
		Wind::import('SRV:design.bo.PwDesignModuleBo');
    	$bo = new PwDesignModuleBo($moduleId);
    	$module = $bo->getModule();
    	if ($module['isused']) return view('');
    	$bo->setStdId();
    	$key = app('design.srv.display.PwDesignDisplay')->bindDataKey($moduleId);
    	$data[$key] = $bo->getData(true, false);
    	->with($data, '__design_data');
    	list($theme,)= $this->getForward()->getWindView()->getTheme();
    	if (is_array($theme)) list($theme, $pack) = $theme;
    	if (!$theme) $theme = 'default';
    	WindFolder::rm(Wind::getRealDir('DATA:compile.template.'.$theme.'.design.segment.'), true);
    	return view('TPL:design.segment.module');
	}
	
	public function getmodulepermissionsAction(Request $request) {
		$other = array('html', 'searchbar', 'image');
		$tab = array();
		$moduleId = (int)$request->get('moduleid', 'post');
		$pageid = (int)$request->get('pageid', 'post');
		$module = $this->_getModuleDs()->getModule($moduleId);
		if(!$module || !$module['isused']) return $this->showError("DESIGN:module.is.delete");
		app('design.PwDesignPermissions');
		$permissions = $this->_getPermissionsService()->getPermissionsForModule($this->loginUser->uid, $moduleId, $pageid);
		if ($permissions <= PwDesignPermissions::NEED_CHECK) return $this->showError("DESIGN:permissions.fail");
		switch ($permissions) {
			case '4':
				if ($module['module_type'] == PwDesignModule::TYPE_DRAG) {
					$tab = array('data','push','add','title','style','property','template','delete');
				} else {
					$tab = array('data','push','add','title','property','template','delete');
				}
				break;
			case '3':
				if ($module['module_type'] == PwDesignModule::TYPE_DRAG) {
					$tab = array('data','push','add','title','style','property','template');
				} else {
					$tab = array('data','push','add','title','property','template');
				}
				break;
			case '2':
				if (in_array($module['model_flag'], $other)) {
					$tab = array('property');
				} else {
					$tab = array('data','push','add');
				}
				break;
			default:
				return $this->showError("DESIGN:permissions.fail");
		}
		
		//对config里的tab进行过滤
		Wind::import('SRV:design.bo.PwDesignModelBo');
		$bo = new PwDesignModelBo($module['model_flag']);
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
	
	public function dosavepageAction(Request $request) {
		$_tmp1 = $_tmp2 = $isunique = false;
		$pageid = (int)$request->get('pageid', 'post');
		$uniqueid = (int)$request->get('uniqueid', 'post');
		$uri = $request->get('uri', 'post');
		$segments = (array)$request->get('segment', 'post');
		$type = $request->get('type', 'post');
		app('design.PwDesignPermissions');
		$permissions = $this->_getPermissionsService()->getPermissionsForPage($this->loginUser->uid,$pageid);
		Wind::import('SRV:design.bo.PwDesignPageBo');
		$pageBo = new PwDesignPageBo($pageid);
		if ($pageBo->getLock()) return $this->showError('DESIGN:page.edit.other.user');
		
		//兼容其它类型模块
		if ($permissions >= PwDesignPermissions::IS_PUSH ){
			$service = $this->_getPageSaveService();
			if ($type == 'isunique') $_tmp1 = true;
			foreach ($segments AS $segment) {
				if ($segment) $_tmp2 = true;
			}
			if ($_tmp1 && $_tmp2) $isunique = true;
			$resource = $service->getNewPageId($pageid, $uniqueid, $isunique);
			if ($resource instanceof ErrorBag) return $this->showError($resource->getError());
			$resource = $service->updateSegment($segments, $resource);
			if ($resource instanceof ErrorBag) return $this->showError($resource->getError());
		} else {
			Wind::import('SRV:design.dm.PwDesignPageDm');
			$ds = $this->_getPageDs();
			$dm = new PwDesignPageDm($pageid);
			$dm->setDesignLock(0, 0);
			$ds->updatePage($dm);
		}
		$this->_getDesignService()->clearCompile();
		$this->_getBakService()->doBak($pageid);
		//->with(urldecode($uri), 'data');
		return $this->showMessage("operate.success", urldecode($uri));
	}

	
	/**
	 * 恢复上一次数据
	 * Enter description here ...
	 */
	public function dorestoreAction(Request $request) {
		$pageid = (int)$request->get('pageid', 'post');
		$step = (int)$request->get('step', 'post');
		$permissions = $this->_getPermissionsService()->getPermissionsForPage($this->loginUser->uid,$pageid);
		if ($permissions < PwDesignPermissions::IS_DESIGN ) return $this->showError("DESIGN:permissions.fail");
		Wind::import('SRV:design.bo.PwDesignPageBo');
		$pageBo = new PwDesignPageBo($pageid);
		if ($pageBo->getLock()) return $this->showError('DESIGN:page.edit.other.user');
		$this->_getRestoreService()->doRestoreBak($pageid);
		$this->_getDesignService()->clearCompile();
		return $this->showMessage("operate.success");

	}
	
	/**
	 * 更新当前页数据
	 * Enter description here ...
	 */
	public function docacheAction(Request $request) {
		$pageid = (int)$request->get('pageid', 'post');
		$pageInfo = $this->_getPageDs()->getPage($pageid);
		if (!$pageInfo) return $this->showError("operate.fail");
		$permissions = $this->_getPermissionsService()->getPermissionsForPage($this->loginUser->uid,$pageid);
		if ($permissions < PwDesignPermissions::IS_PUSH ) return $this->showError("DESIGN:permissions.fail");
		Wind::import('SRV:design.bo.PwDesignPageBo');
		$pageBo = new PwDesignPageBo($pageid);
		if ($pageBo->getLock()) return $this->showError('DESIGN:page.edit.other.user');
		$list = $this->_getModuleDs()->getByPageid($pageid);
		Wind::import('SRV:design.srv.data.PwAutoData');
		foreach ($list AS $id=>$v) {
			$id = (int)$id;
			if ($id < 1) continue;
			$srv = new PwAutoData($id);
			$srv->addAutoData();
		}
		$this->_getDesignService()->clearCompile();
		return $this->showMessage("operate.success");
	}
	
	/**
	 * 清空当前页设计数据
	 * Enter description here ...
	 * @see ImportController->dorunAction
	 */
	public function doclearAction(Request $request) {
		$pageid = (int)$request->get('pageid', 'post');
		Wind::import('SRV:design.bo.PwDesignPageBo');
    	$pageBo = new PwDesignPageBo($pageid);
		$pageInfo = $pageBo->getPage();
		if (!$pageInfo) return $this->showError("operate.fail");
		$permissions = $this->_getPermissionsService()->getPermissionsForPage($this->loginUser->uid,$pageid);
		if ($permissions < PwDesignPermissions::IS_DESIGN ) return $this->showError("DESIGN:permissions.fail");
		Wind::import('SRV:design.bo.PwDesignPageBo');
		$pageBo = new PwDesignPageBo($pageid);
		if ($pageBo->getLock()) return $this->showError('DESIGN:page.edit.other.user');
		
		
		$ids = explode(',', $pageInfo['module_ids']);
		$names = explode(',', $pageInfo['module_names']);
		$moduleDs = $this->_getModuleDs();
		$bakDs = $this->_getBakDs();
		$dataDs = $this->_getDataDs();
		$pushDs = $this->_getPushDs();
		$imageSrv = app('design.srv.PwDesignImage');
		// module
		$moduleDs->deleteByPageId($pageid);
		// data && push
		foreach ($ids AS $id) {
			$dataDs->deleteByModuleId($id);
			$pushDs->deleteByModuleId($id);
			$imageSrv->clearFolder($id);
		}
		
		//structure
		$ds = $this->_getStructureDs();
		foreach ($names AS $name) {
			$ds->deleteStruct($name);
		}
		
		//segment
		$this->_getSegmentDs()->deleteSegmentByPageid($pageid);
		
		//bak
		$bakDs->deleteByPageId($pageid);

		$tplPath = $pageBo->getTplPath();
		$this->_getDesignService()->clearTemplate($pageid, $tplPath);
		if ($pageInfo['page_type'] == PwDesignPage::PORTAL) {
			
			Wind::import('SRV:design.dm.PwDesignPortalDm');
			$dm = new PwDesignPortalDm($pageInfo['page_unique']);
			$dm->setTemplate($tplPath);
			$this->_getPortalDs()->updatePortal($dm);
			
			$srv = app('design.srv.PwDesignService');
			$result = $srv->defaultTemplate($pageid, $tplPath);
		} else {
			$this->_getPageDs()->deletePage($pageid);
		}
		$this->_getDesignService()->clearCompile();
		return $this->showMessage("operate.success");
	}
	
	/**
	 * 被占用退出
	 * Enter description here ...
	 */
	public function exitAction(Request $request) {
		$uri = $request->get('uri', 'post');
		->with(urldecode($uri), 'data');
		return $this->showMessage("operate.success");
	}
	
	public function doexitAction(Request $request) {
		$pageid = (int)$request->get('pageid', 'post');
		$uri = $request->get('uri', 'post');
		$pageDs = $this->_getPageDs();
		$pageInfo = $pageDs->getPage($pageid);
		if (!$pageInfo) return $this->showError("operate.fail");
		$permissions = $this->_getPermissionsService()->getPermissionsForPage($this->loginUser->uid,$pageid);
		if ($permissions < PwDesignPermissions::NEED_CHECK ) return $this->showError("DESIGN:permissions.fail");
		Wind::import('SRV:design.bo.PwDesignPageBo');
		$pageBo = new PwDesignPageBo($pageid);
		if ($pageBo->getLock()) return $this->showError('DESIGN:page.edit.other.user');
		
		$srv =$this->_getRestoreService();
		$srv->doRestoreSnap($pageid);
		//编辑模式解锁
		Wind::import('SRV:design.dm.PwDesignPageDm');
		$dm = new PwDesignPageDm($pageid);
		$dm->setDesignLock(0, 0);
		$this->_getPageDs()->updatePage($dm);
		$this->_getDesignService()->clearCompile();
		//->with(urldecode($uri), 'data');
		return $this->showMessage("operate.success", urldecode($uri));
	}
	
	/**
	 * 设计模计轮循加锁
	 * Enter description here ...
	 */
	public function lockdesignAction(Request $request) {
		$pageid = (int)$request->get('pageid', 'post');
		$pageInfo = $this->_getPageDs()->getPage($pageid);
		if (!$pageInfo) return $this->showError("operate.fail");
		app('design.PwDesignPermissions');
		$permissions = $this->_getPermissionsService()->getPermissionsForPage($this->loginUser->uid,$pageid);
		if ($permissions <= PwDesignPermissions::NEED_CHECK) return $this->showError("DESIGN:permissions.fail");
		list($uid,$time) = explode('|', $pageInfo['design_lock']);
		if ($uid != $this->loginUser->uid) return $this->showError("operate.fail");
		Wind::import('SRV:design.dm.PwDesignPageDm');
		$dm = new PwDesignPageDm($pageid);
		$dm->setDesignLock($this->loginUser->uid, Tool::getTime());
		$this->_getPageDs()->updatePage($dm);
		return $this->showMessage("operate.success");
	}

	private function _getDesignService() {
		return app('design.srv.PwDesignService');
	}
	
	private function _getRestoreService() {
		return app('design.srv.PwRestoreService');
	}
	
	private function _getBakService() {
		return app('design.srv.PwPageBakService');
	}
	
	private function _getPageSaveService() {
		return app('design.srv.PwDesignPageSave');
	}

	private function _getPermissionsService() {
		return app('design.srv.PwDesignPermissionsService');
	}
	
	private function _getStructureDs() {
		return app('design.PwDesignStructure');
	}
	
	private function _getModuleDs() {
		return app('design.PwDesignModule');
	}
	
	private function _getPageDs() {
		return app('design.PwDesignPage');
	}
	
	private function _getBakDs() {
		return app('design.PwDesignBak');
	}
	
	private function _getSegmentDs() {
		return app('design.PwDesignSegment');
	}
	
	private function _getDataDs() {
		return app('design.PwDesignData');
	}
	
	private function _getPushDs() {
		return app('design.PwDesignPush');
	}
	
	private function _getPortalDs() {
		return app('design.PwDesignPortal');
	}
	
}
?>