<?php
Wind::import('APPS:u.service.helper.PwUserHelper');

/**
 * 左边导航和资料tab扩展
 *
 * @author xiaoxia.xu <xiaoxia.xuxx@aliyun-inc.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: BaseProfileController.php 22678 2012-12-26 09:22:23Z jieyin $
 * @package src.products.u.controller.profile
 */
class BaseProfileController extends Controller{
	
	protected $defaultGroups = array(
			0 => array('name' => '普通组', 'gid' => '0'), 
		);
	protected $bread = array();

	/* (non-PHPdoc)
	 * @see PwBaseController::beforeAction()
	 */
	public function beforeAction($handlerAdapter) {
		parent::beforeAction($handlerAdapter);
		if (!$this->loginUser->isExists()) {
			return redirect('u/login/run', array('_type' => $request->get('_type'))));
		}
		if (!$request->getIsAjaxRequest()) {
			$this->setLayout('TPL:profile.profile_layout');
		}
	}
	
	/**
	 * 获得个人中心菜单服务
	 *
	 * @return PwUserProfileMenu
	 */
	protected function getMenuService() {
		return app('APPS:profile.service.PwUserProfileMenu');
	}
	
	/** 
	 * 设置当前设置项
	 * 
	 * @param string $left
	 */
	protected function setCurrentLeft($left = '', $tab = '') {
		$menus = $this->getMenuService()->getMenus();
		$left = $left ? $left : $request->get('_left');
		$tab = $tab ? $tab : $request->get('_tab');
		list($left, $tab) = $this->getMenuService()->getCurrentTab($left, $tab);
		$currentMenu = $menus[$left];
		$tab && $currentMenu = $currentMenu['tabs'][$tab];
		if (!isset($currentMenu['url'])) {
			return redirect('profile/extends/run', array('_left' => $left, '_tab' => $tab)));
		}
		
		$menus[$left]['current'] = 'current';
		$this->bread['left'] = array('url' => url($menus[$left]['url'], array('_left' => $left)), 'title' => $menus[$left]['title']);
		Core::setGlobal($menus, 'profileLeft');
		
		if ($menus[$left]['tabs']) {
			$menus[$left]['tabs'][$tab]['current'] = 'current';
			$this->appendBread($menus[$left]['tabs'][$tab]['title'], url($menus[$left]['tabs'][$tab]['url'], array('_tab' => $tab, '_left' => $left)));
			->with($menus[$left]['tabs'], '_tabs');
		}
	}
	
	/**
	 * 设置面包屑
	 *
	 * @param string $title
	 * @param string $url
	 */
	protected function appendBread($title, $url) {
		$this->bread[] = array('url' => $url, 'title' => $title);
		return $this;
	}
	
	/* (non-PHPdoc)
	 * @see PwBaseController::afterAction()
	 */
	public function afterAction($handlerAdapter) {
		parent::afterAction($handlerAdapter);
		$bread = array($this->bread['left']);
		unset($this->bread['left']);
		$this->bread && $bread = array_merge($bread, $this->bread);
		Core::setGlobal($bread, 'profileBread');
	}
}