<?php
Wind::import('LIB:base.PwBaseController');
/**
 * the last known user to change this file in the repository  <$LastChangedBy: gao.wanggao $>
 * @author $Author: gao.wanggao $ Foxsee@aliyun.com
 * @copyright ?2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: IndexController.php 25125 2013-03-05 03:29:29Z gao.wanggao $ 
 * @package 
 */

class IndexController extends Controller{
	
	public function run() {
		$id = (int)$request->get('id', 'get');
		$portal = $this->_getPortalDs()->getPortal($id);
		if (!$portal) return $this->showError("page.status.404");
		if (!$portal['isopen']) {
			$permissions = $this->_getPermissionsService()->getPermissionsForUserGroup($this->loginUser->uid);
			if ($permissions < 1) return $this->showError("page.status.404");
		}
		
		->with($portal, 'portal');
		if($portal['navigate']) {
			->with($this->headguide($portal['title']), 'headguide');
		}
		if ($portal['template']) {
			$url =  WindUrlHelper::checkUrl(PUBLIC_THEMES . '/portal/local/' . $portal['template'], PUBLIC_URL);
			$design['url']['css'] = $url . '/css';
			$design['url']['images'] = $url . '/images';
			$design['url']['js'] = $url . '/js';
			Core::setGlobal($design, 'design');
			return view("THEMES:portal.local.".$portal['template'].".template.index");
		} else {
			return view("TPL:special.index_run");
		}
		//$this->getForward()->getWindView()->compileDir = 'DATA:design.default.' . $id;
		$this->setT($portal['template'], 'THEMES:portal.local');
		Wind::import('SRV:seo.bo.PwSeoBo');
		$seoBo = PwSeoBo::getInstance();
		$seoBo->init('area', 'custom', $id);
		$seoBo->set('{pagename}', $portal['title']);
		Core::setV('seo', $seoBo);
	}
	
	protected function headguide($protalname) {
		$bbsname = Core::C('site', 'info.name');
		$headguide = '<a href="' . url('') . '" title="' . $bbsname . '" class="home">首页</a>';
		return $headguide . '<em>&gt;</em>' . Security::escapeHTML($protalname);
	}
	
	
	private function _getPortalDs() {
		return app('design.PwDesignPortal');
	}
	
	protected function _getPermissionsService() {
		return app('design.srv.PwDesignPermissionsService');
	}
	
	protected function setT($theme, $themePack) {
		$this->getForward()->getWindView()->setTheme($theme, $themePack);
	}

}
?>