<?php
Wind::import('ADMIN:library.AdminBaseController');
Wind::import('SRV:seo.dm.PwSeoDm');
/**
 * seo模式的基础controller
 *
 * @author Shi Long <long.shi@alibaba-inc.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.windframework.com
 * @version $Id$
 * @package modules.seo.admin
 */
class AdminBaseSeoController extends AdminBaseController {

	/**
	 * 设置后台菜单
	 *
	 * @param string $action        	
	 * @return string
	 */
	protected function setTab($mod) {
		//$this->setLayout('seolayout');
		$tabs = $this->_extendServices()->getTabs();
		reset($tabs);
		$mod || $mod = key($tabs);
		$tabs[$mod]['current'] = 'current';
		->with($tabs, 'tabs');
		$this->getAllPagesAndCodes($mod);
		return $tabs[$mod]['url'];
	}

	/**
	 * 获得所有页面和占位符
	 *
	 * @param string $mode        	
	 * @return array
	 */
	protected function getAllPagesAndCodes($mode) {
		->with(
			array(
				'codes' => $this->_extendServices()->getCodes($mode), 
				'pages' => $this->_extendServices()->getPages($mode)));
	}

	/**
	 *
	 * @return PwSeo
	 */
	protected function _seoDs() {
		return app('seo.PwSeo');
	}

	/**
	 *
	 * @return PwSeoService
	 */
	protected function _seoService() {
		return app('seo.srv.PwSeoService');
	}

	/**
	 *
	 * @return PwSeoExtends
	 */
	protected function _extendServices() {
		return app('APPS:seo.service.PwSeoExtends');
	}
}

?>