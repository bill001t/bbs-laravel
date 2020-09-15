<?php
Wind::import('LIB:base.PwBaseController');
/**
 * the last known user to change this file in the repository  <$LastChangedBy: long.shi $>
 * @author $Author: long.shi $ Foxsee@aliyun.com
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: IndexController.php 23994 2013-01-18 03:51:46Z long.shi $ 
 * @package 
 */

class IndexController extends Controller{
	
	public function run() {
		return $this->showError("page.status.404");
	}
	
	/*
	public function run() {
		$id = (int)$request->get('id', 'get');
		$portal = $this->_getPortalDs()->getPortal($id);
		if (!$portal) return $this->showError("page.status.404");
		if (!$portal['isopen']) {
			$permissions = $this->_getPermissionsService()->getPermissionsForUserGroup($this->loginUser->uid);
			if ($permissions < 1) return $this->showError("page.status.404");
		}
		
		Wind::import('SRV:seo.bo.PwSeoBo');
		$seoBo->setCustomSeo($portal['title'],$portal['keywords'],$portal['description']);

		->with($portal, 'portal');
		if($portal['navigate']) {
			->with($this->headguide($portal['title']), 'headguide');
		}
		if ($portal['template']) {
			$url =  WindUrlHelper::checkUrl(PUBLIC_THEMES . '/design/' . $portal['template'], PUBLIC_URL);
			$design['url']['css'] = $url . '/css';
			$design['url']['images'] = $url . '/images';
			$design['url']['js'] = $url . '/js';
			Core::setGlobal($design, 'design');
			return view("THEMES:design.".$portal['template'].".template.index");
		} else {
			return view("TPL:design.portal.default");
		}
		//$this->getForward()->getWindView()->compileDir = 'DATA:design.default.' . $id;
	}
	
	protected function headguide($protalname) {
		$bbsname = Core::C('site', 'info.name');
		$headguide = '<a href="' . 'bbs/index/run' . '" title="' . $bbsname . '" class="home">' . $bbsname . '</a>';
		return $headguide . '<em>&gt;</em>' . Security::escapeHTML($protalname);
	}
	
	
	private function _getPortalDs() {
		return app('design.PwDesignPortal');
	}
	
	protected function _getPermissionsService() {
		return app('design.srv.PwDesignPermissionsService');
	}
	*/

}
?>