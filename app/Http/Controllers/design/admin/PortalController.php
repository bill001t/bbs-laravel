<?php
Wind::import('ADMIN:library.AdminBaseController');
/**
 * the last known user to change this file in the repository  <$LastChangedBy: gao.wanggao $>
 * @author $Author: gao.wanggao $ Foxsee@aliyun.com
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: PortalController.php 28818 2013-05-24 10:10:46Z gao.wanggao $ 
 * @package 
 */

class PortalController extends AdminBaseController {
	
	public function run() {
		$page = (int)$request->get('page','get');
		$perpage = 10;
		$args = array();
		$page =  $page > 1 ? $page : 1;
		list($start, $perpage) = Tool::page2limit($page, $perpage);
		Wind::import('SRV:design.srv.vo.PwDesignPortalSo');
		$vo = new PwDesignPortalSo();
		$ds = $this->_getPortalDs();
		$count = $ds->countPartal($vo);
		$list = $ds->searchPortal($vo, $start, $perpage);
		$pageList = $this->_getPageDs()->fetchPageByTypeUnique(PwDesignPage::PORTAL, array_keys($list));
		foreach ($pageList AS $k=>$v) {
			foreach ($list AS $_k=>$_v) {
				if ($v['page_unique'] == $_k) $list[$_k]['page_id']	= $k;
			}
		}
		->with($list ,'list');
		->with($count, 'count');
		->with($page, 'page');
		->with($perpage, 'perpage');
		->with(ceil($count/$perpage), 'totalpage');
		->with('design/portal/run', 'pageurl');
	}
	
	public function deleteAction(Request $request) {
		$portalid = (int)$request->get('id','post');
		$portal = $this->_getPortalDs()->getPortal($portalid);
		$pageInfo = $this->_getPageDs()->getPageByTypeAndUnique(PwDesignPage::PORTAL,$portalid);
		Wind::import('SRV:design.bo.PwDesignPageBo');
		$pageBo = new PwDesignPageBo($pageInfo['page_id']);
		if ($pageInfo) {
			$ids = explode(',', $pageInfo['module_ids']);
			$names = explode(',', $pageInfo['module_names']);
			$moduleDs = $this->_getModuleDs();
			$bakDs = $this->_getBakDs();
			$dataDs = $this->_getDataDs();
			$pushDs = $this->_getPushDs();
			$imageSrv = app('design.srv.PwDesignImage');
			$moduleDs->deleteByPageId($pageInfo['page_id']);
			// module&& data && push
			$list = app('design.PwDesignModule')->getByPageid($this->pageid);
			foreach ($list AS $id=>$v) {
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
			$this->_getSegmentDs()->deleteSegmentByPageid($pageInfo['page_id']);
			$this->_getPageDs()->deletePage($pageInfo['page_id']);
			$this->_getPermissionsDs()->deleteByTypeAndDesignId(PwDesignPermissions::TYPE_PAGE, $pageInfo['page_id']);
		}
		$this->_getDesignService()->clearTemplate($pageBo->pageid, $pageBo->getTplPath());
		if ($this->_getPortalDs()->deletePortal($portalid)) {
			if ($portal['cover']) {
				$ext = strrchr($portal['cover'],".");
				$filename = 'portal/'.$portalid . $ext;
				Tool::deleteAttach($filename);
			}
			return $this->showMessage("operate.success");
		}
		return $this->showMessage("operate.fail");
	}
	
	public function batchopenAction(Request $request) {
		$ids = $request->get('ids','post');
		$isopen = $request->get('isopen','post');
		$ds = $this->_getPortalDs();
		foreach ($ids AS $id) {
			$ds->updatePortalOpen($id, $isopen[$id]);
		}
		return $this->showMessage("operate.success");

	}
	
	/*
	public function batchdeleteAction(Request $request) {
		$ids = (int)$request->get('ids','post');
		if ($this->_getPortalDs()->batchDelete($ids)) return $this->showMessage("operate.success");
		return $this->showMessage("operate.fail");
	}*/
	
	private function _getDesignService() {
		return app('design.srv.PwDesignService');
	}
	
	private function _getPermissionsDs() {
		return app('design.PwDesignPermissions');
	}
	
	private function _getStructureDs() {
		return app('design.PwDesignStructure');
	}
	
	private function _getModuleDs() {
		return app('design.PwDesignModule');
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
	
	private function _getPageDs() {
		return app('design.PwDesignPage');
	}
}