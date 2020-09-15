<?php
Wind::import('SRV:user.validator.PwUserValidator');
Wind::import('SRV:user.PwUserBan');
Wind::import('APPS:profile.service.PwUserProfileExtends');
		
/**
 * 用户资料页面
 *
 * @author xiaoxia.xu <xiaoxia.xuxx@aliyun-inc.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: ExtendsController.php 22678 2012-12-26 09:22:23Z jieyin $
 * @package src.products.u.controller.profile
 */
class ExtendsController extends Controller{
	
	/* (non-PHPdoc)
	 * @see PwBaseController::beforeAction()
	 */
	public function beforeAction($handlerAdapter) {
		parent::beforeAction($handlerAdapter);
		if (!$this->loginUser->isExists()) {
			return redirect('u/login/run', array('_type' => $request->get('_type'))));
		}
	}
    
    /* (non-PHPdoc)
	 * @see PwBaseController::run()
	 */
	public function run() {
		/* @var $profileMenuSrv PwUserProfileMenu */
		$profileMenuSrv = app('APPS:profile.service.PwUserProfileMenu');
		list($_left, $_tab) = $profileMenuSrv->getCurrentTab($request->get('_left'), $request->get('_tab'));
		$menus = $profileMenuSrv->getMenus();
		$currentMenu = $menus[$_left];
		($_tab) && $currentMenu = $menus[$_left]['tabs'][$_tab];
		if (!$currentMenu) return $this->showError('USER:profile.extends.noexists');
		
		$extendsSrv = new PwUserProfileExtends($this->loginUser);
		$extendsSrv->setCurrent($_left, $_tab);
		$this->runHook('c_profile_extends_run', $extendsSrv);
		->with($extendsSrv, 'hookSrc');
		->with($menus, '_menus');
		->with($_left, '_left');
		->with($_tab, '_tab');
		return view('extends_run');
	}
	
	/**
	 * 接受表单处理
	 */
	public function dorunAction(Request $request) {
		/* @var $profileMenuSrv PwUserProfileMenu */
		$profileMenuSrv = app('APPS:profile.service.PwUserProfileMenu');
		list($_left, $_tab) = $profileMenuSrv->getCurrentTab($request->get('_left'), $request->get('_tab'));
		$menus = $profileMenuSrv->getMenus();
		$currentMenu = $menus[$_left];
		($_tab) && $currentMenu = $menus[$_left]['tabs'][$_tab];
		if (!$currentMenu) return $this->showError('USER:profile.extends.noexists');
		
		$extendsSrv = new PwUserProfileExtends($this->loginUser);
		$extendsSrv->setCurrent($_left, $_tab);
		$this->runHook('c_profile_extends_dorun', $extendsSrv);
		$r = $extendsSrv->execute();
		if ($r instanceof ErrorBag) {
			return $this->showError($r->getError());
		}
		return $this->showMessage('success');
	}
}