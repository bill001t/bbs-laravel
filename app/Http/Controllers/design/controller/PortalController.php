<?php
Wind::import('LIB:base.PwBaseController');
/**
 * the last known user to change this file in the repository  <$LastChangedBy: gao.wanggao $>
 * @author $Author: gao.wanggao $ Foxsee@aliyun.com
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: PortalController.php 24103 2013-01-21 10:15:47Z gao.wanggao $ 
 * @package 
 */
class PortalController extends Controller{
	
	public function beforeAction($handlerAdapter) {
		parent::beforeAction($handlerAdapter);
		if ($this->loginUser->uid < 1)  return $this->showError('SPACE:user.not.login');
		app('design.PwDesignPermissions');
		$permissions = $this->_getPermissionsService()->getPermissionsForUserGroup($this->loginUser->uid);
		if ($permissions < PwDesignPermissions::IS_DESIGN) return $this->showError("DESIGN:permissions.fail");
	}

	
	public function addAction(Request $request) {
		//版块域名
		$domain_isopen = Core::C('domain', 'special.isopen');
		if ($domain_isopen) {
			$root = Core::C('domain', 'special.root');
			->with($root, 'root');
		}
	}
	
	public function doaddAction(Request $request) {
		$ds = $this->_getPortalDs();
		$title = $request->get('title', 'post');
		$coverfrom = (int)$request->get('coverfrom', 'post');
		$pagename = $request->get('pagename', 'post');
		$domain = $request->get('domain', 'post'); //TODO
		if (!$title)  return $this->showError("DESIGN:title.is.empty");
		if (!$pagename)  return $this->showError("DESIGN:pagename.is.empty");
		if (!$this->_validator($pagename))  return $this->showError("DESIGN:pagename.validator.fail");
		if ($domain && !$this->_validator($domain))  return $this->showError("DESIGN:domain.validator.fail");
		
		if ($ds->countPortalByPagename($pagename)) return $this->showError("DESIGN:pagename.already.exists");
		Wind::import('SRV:design.dm.PwDesignPortalDm');
 		$dm = new PwDesignPortalDm();
 		$dm->setPageName($pagename)
 			->setTitle($title)
 			->setDomain($domain) 
 			->setIsopen((int)$request->get('isopen', 'post'))
 			->setHeader((int)$request->get('isheader', 'post'))
 			->setNavigate((int)$request->get('isnavigate', 'post'))
 			->setFooter((int)$request->get('isfooter', 'post'))
 			->setKeywords($request->get('keywords', 'post'))
 			->setDescription($request->get('description', 'post'))
 			
 			//->setTemplate($request->get('isfooter', 'post'))
 			->setCreatedUid($this->loginUser->uid)
 			->setCreatedTime(Tool::getTime());
		$resource = $ds->addPortal($dm);
		if ($resource instanceof ErrorBag) return $this->showError($resource->getError());
		$id = (int)$resource;
		if ($coverfrom == 2) {
			$upload = $this->_upload($id);
			$cover = Tool::getPath($upload['path'].$upload['filename']);
		} else {
			$cover = $request->get('webcover', 'post');
			$cover =  (preg_match("/^http:\/\/(.*)$/", $cover)) ? $cover : ''; 
		}
		if ($cover) {
			$dm = new PwDesignPortalDm($id);
			$dm->setCover($cover);
			$ds->updatePortal($dm);
		}
		
		//二级域名start
		list($domain, $root) = $request->get(array('domain', 'root'), 'post');
		if ($root) {
			if (!$domain)
				app('domain.PwDomain')->deleteByDomainKey("special/index/run?id=$id");
			else {
				$r = app('domain.srv.PwDomainService')->isDomainValid($domain, $root, "special/index/run?id=$id");
				if ($r instanceof ErrorBag) return $this->showError($r->getError());
				Wind::import('SRV:domain.dm.PwDomainDm');
				$dm = new PwDomainDm();
				$dm->setDomain($domain)
				->setDomainKey("special/index/run?id=$id")
				->setDomainType('special')
				->setRoot($root)
				->setFirst($domain[0])
				->setId($id);
				app('domain.PwDomain')->replaceDomain($dm);
			}
			app('domain.srv.PwDomainService')->flushAll();
		}
		//二级域名end
		
		//seo
 		Wind::import('SRV:seo.dm.PwSeoDm');
 		$dm = new PwSeoDm();
 		$dm->setMod('area')
		   ->setPage('custom')
		   ->setParam($id)
		   ->setTitle($title)
		   ->setKeywords($request->get('keywords', 'post'))
		   ->setDescription($request->get('description', 'post'));
 		app('seo.srv.PwSeoService')->batchReplaceSeoWithCache($dm);
		
		return $this->showMessage("operate.success", "special/index/run?id=".$resource, true);
	}
	
	public function editAction(Request $request) {
		$id = (int)$request->get('id', 'get');
		$portal = $this->_getPortalDs()->getPortal($id);
		if (!$portal) return $this->showError("page.status.404");
		
		
		//版块域名
		$domain_isopen = Core::C('domain', 'special.isopen');
		if ($domain_isopen) {
			$root = Core::C('domain', 'special.root');
			$result = app('domain.PwDomain')->getByDomainKey("special/index/run?id=$id");
			$domain = isset($result['domain']) ? $result['domain'] : '';
			->with($root, 'root');
			->with($domain, 'domain');
		}
		
		//seo
		$seo = app('seo.PwSeo')->getByModAndPageAndParam('area', 'custom', $id);
		$portal['title'] = $seo['title'];
		$portal['description'] = $seo['description'];
		$portal['keywords'] = $seo['keywords'];
		->with($portal, 'portal');
	}
	
	public function doeditAction(Request $request) {
		$id = (int)$request->get('portalid', 'post');
		$title = $request->get('title', 'post');
		$coverfrom = (int)$request->get('coverfrom', 'post');
		$pagename = $request->get('pagename', 'post');
		$keywords = $request->get('keywords', 'post');
		$description = $request->get('description', 'post');
		if (!$title)  return $this->showError("DESIGN:title.is.empty");
		if (!$pagename)  return $this->showError("DESIGN:pagename.is.empty");
		//二级域名start
		list($domain, $root) = $request->get(array('domain', 'root'), 'post');
		if ($root) {
			if (!$domain)
				app('domain.PwDomain')->deleteByDomainKey("special/index/run?id=$id");
			else {
				$r = app('domain.srv.PwDomainService')->isDomainValid($domain, $root, "special/index/run?id=$id");
				if ($r instanceof ErrorBag) return $this->showError($r->getError());
				Wind::import('SRV:domain.dm.PwDomainDm');
				$dm = new PwDomainDm();
				$dm->setDomain($domain)
				->setDomainKey("special/index/run?id=$id")
				->setDomainType('special')
				->setRoot($root)
				->setFirst($domain[0])
				->setId($id);
				app('domain.PwDomain')->replaceDomain($dm);
			}
			app('domain.srv.PwDomainService')->flushAll();
		}
		//二级域名end
		
		if (!$this->_validator($pagename))  return $this->showError("DESIGN:pagename.validator.fail");
		$ds = $this->_getPortalDs();
		$portal = $ds->getPortal($id);
		if (!$portal) return $this->showError("operate.fail");
		$count = $ds->countPortalByPagename($pagename);
		if ($portal['pagename'] != $pagename && $count >= 1){
			return $this->showError("DESIGN:pagename.already.exists");
		} 
		
		if ($coverfrom == 2) {
			$cover = '';
			$upload = $this->_upload($id);
			if ($upload['filename']) {
				$cover = Tool::getPath($upload['path'].$upload['filename']);
			}
		} else {
			$cover = $request->get('webcover', 'post');
			$cover =  (preg_match("/^http:\/\/(.*)$/", $cover)) ? $cover : ''; 
		}
		
		
		Wind::import('SRV:design.dm.PwDesignPortalDm');
 		$dm = new PwDesignPortalDm($id);
 		$dm->setPageName($pagename)
 			->setTitle($title)
 			->setCover($cover) 
 			->setDomain($domain) 
 			->setIsopen((int)$request->get('isopen', 'post'))
 			->setHeader((int)$request->get('isheader', 'post'))
 			->setNavigate((int)$request->get('isnavigate', 'post'))
 			->setFooter((int)$request->get('isfooter', 'post'))
 			->setKeywords($keywords)
 			->setDescription($description);
		$resource = $ds->updatePortal($dm);
		if ($resource instanceof ErrorBag) return $this->showError($resource->getError());
		$pageInfo = $this->_getPageDs()->getPageByTypeAndUnique(PwDesignPage::PORTAL, $id);
		//更新页面名称
		Wind::import('SRV:design.dm.PwDesignPageDm');
 		$dm = new PwDesignPageDm($pageInfo['page_id']);
 		$dm->setName($title);
 		$this->_getPageDs()->updatePage($dm);
 		
 		//seo
 		Wind::import('SRV:seo.dm.PwSeoDm');
 		$dm = new PwSeoDm();
 		$dm->setMod('area')
		   ->setPage('custom')
		   ->setParam($id)
		   ->setTitle($title)
		   ->setKeywords($keywords)
		   ->setDescription($description);
 		app('seo.srv.PwSeoService')->batchReplaceSeoWithCache($dm);
		return $this->showMessage("operate.success", "special/index/run?id=".$id, true);
	}
	
	private function _validator($string) {
		if (preg_match('/^[\dA-Za-z\_]+$/', $string)) return true;
		return false;
	}
	
	private function _upload($portalId = 0) {
 		Wind::import('SRV:upload.action.PwPortalUpload');
		Wind::import('LIB:upload.PwUpload');
		$bhv = new PwPortalUpload($portalId);
		$upload = new PwUpload($bhv);
		if (($result = $upload->check()) === true) $result = $upload->execute();
		if ($result !== true) return $this->showError($result->getError());
		return $bhv->getAttachInfo();
 	}
	
 		
	protected function _getPermissionsService() {
		return app('design.srv.PwDesignPermissionsService');
	}

	private function _getPortalDs() {
		return app('design.PwDesignPortal');
	}
	
	private function _getPageDs() {
		return app('design.PwDesignPage');
	}
}

?>