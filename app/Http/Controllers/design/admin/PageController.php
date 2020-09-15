<?php
Wind::import('ADMIN:library.AdminBaseController');
/**
 * the last known user to change this file in the repository  <$LastChangedBy: gao.wanggao $>
 * @author $Author: gao.wanggao $ Foxsee@aliyun.com
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: PageController.php 28936 2013-05-31 02:50:17Z gao.wanggao $ 
 * @package 
 */

class PageController extends AdminBaseController {
	
	public function run() {
		$page = (int)$request->get('page','get');
		$perpage = 10;
		$args = array();
		$page =  $page > 1 ? $page : 1;
		list($start, $perpage) = Tool::page2limit($page, $perpage);
		$list = $this->_getPageDs()->getPageList(PwDesignPage::SYSTEM, $start, $perpage);
		$count =  $this->_getPageDs()->countPage(PwDesignPage::SYSTEM);
		$sysPage = app('design.srv.router.PwDesignRouter')->get();
		foreach ($list AS &$v) {
			if (isset($sysPage[$v['page_router']])){ 
				list($pagename, $unique) = $sysPage[$v['page_router']];
			}
			list($m,$c,$a,$id) = explode('|', $v['page_router']);
			if ($unique) {
				$v['url'] = url($m .'/'. $c .'/'. $a, array($unique => $v['page_unique']), '', 'pw');
			} else {
				$v['url'] = url($m .'/'. $c .'/'. $a, array(), '', 'pw');
			}
			$sep = strpos($v['url'],'?') === false ? '?' : '&';
			$v['designurl'] = $v['url'].$sep.'design=1';
		}
		
		->with($list,'list');
		->with($count, 'count');
		->with($page, 'page');
		->with($perpage, 'perpage');
		->with(ceil($count/$perpage), 'totalpage');
		->with('design/page/run', 'pageurl');
	}
	
	public function getModuleOptionAction(Request $request) {
		$option = '';
		$pageid = (int)$request->get('pageid','post');
		$pageInfo = $this->_getPageDs()->getPage($pageid);
		if (!$pageInfo['module_ids']) return $this->showMessage("operate.fail");
		$modules = $this->_getModuleDs()->fetchModule(explode(',', $pageInfo['module_ids']));
		
		foreach ($modules AS $v) {
			$option .= '<option value="'.$v['module_id'].'">'.$v['module_name'].'</option>';
		}
		->with($option, 'html');
		return $this->showMessage("operate.success");
	}
	
	/**
	 * 清空当前页设计数据
	 * Enter description here ...
	 * @see ImportController->dorunAction
	 */
	public function doclearAction(Request $request) {
		$pageid = (int)$request->get('id', 'post');
		Wind::import('SRV:design.bo.PwDesignPageBo');
    	$pageBo = new PwDesignPageBo($pageid);
		$pageInfo = $pageBo->getPage();
		if (!$pageInfo) return $this->showError("operate.fail");
		
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
		
		$tplPath = $pageBo->getTplPath();
		$this->_getDesignService()->clearTemplate($pageid, $tplPath);
		//bak
		$bakDs->deleteByPageId($pageid);
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
	
	private function _getDesignService() {
		return app('design.srv.PwDesignService');
	}
	
	private function _getModuleDs() {
		return app('design.PwDesignModule');
	}
	
	private function _getPageDs() {
		return app('design.PwDesignPage');
	}
	
	private function _getStructureDs() {
		return app('design.PwDesignStructure');
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