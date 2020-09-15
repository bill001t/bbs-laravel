<?php
Wind::import('APPS:manage.controller.BaseManageController');

/**
 * 前台管理面板 - 用户审核
 *
 * @author xiaoxia.xu <xiaoxia.xuxx@aliyun-inc.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: UserController.php 23994 2013-01-18 03:51:46Z long.shi $
 * @package 
 */
class UserController extends BaseManageController {
	
	private $perpage = 20;

	/* (non-PHPdoc)
	 * @see BaseManageController::beforeAction()
	 */
	public function beforeAction($handlerAdapter) {
		parent::beforeAction($handlerAdapter);
		$result = $this->loginUser->getPermission('panel_user_manage', false, array());
		if (!$result['user_check']) {
			return $this->showError('BBS:manage.thread_check.right.error');
		}
	}
	
	/* (non-PHPdoc)
	 * @see WindController::run()
	 */
	public function run() {
		list($page, $perpage) = $request->get(array('page', 'perpage'));
		$page = $page ? $page : 1;
		$perpage = $perpage > 0 ? $perpage : $this->perpage;
		$count = $this->_getDs()->countUnChecked();
		$list = array();
		if ($count > 0) {
			$totalPage = ceil($count/$perpage);
			$page > $totalPage && $page = $totalPage;
			$result = $this->_getDs()->getUnCheckedList($perpage, intval(($page - 1) * $perpage));
			/* @var $userDs PwUser */
			$userDs = app('user.PwUser');
			$list = $userDs->fetchUserByUid(array_keys($result), PwUser::FETCH_MAIN + PwUser::FETCH_INFO);
			$list = Utility::mergeArray($result, $list);
		}
		->with($count, 'count');
		->with($page, 'page');
		->with($perpage, 'perpage');
		->with(array('perpage' => $perpage), 'args');
		->with($list, 'list');
		
		// seo设置
		Wind::import('SRV:seo.bo.PwSeoBo');
		$seoBo = PwSeoBo::getInstance();
		$lang = Wind::getComponent('i18n');
		$seoBo->setCustomSeo($lang->getMessage('SEO:manage.user.run.title'), '', '');
		Core::setV('seo', $seoBo);
	}
	
	/** 
	 * 电子邮件用户激活
	 */
	public function emailAction(Request $request) {
		list($page, $perpage) = $request->get(array('page', 'perpage'));
		$page = $page ? $page : 1;
		$perpage = $perpage ? $perpage : $this->perpage;
		$count = $this->_getDs()->countUnActived();
		$list = array();
		if ($count > 0) {
			$totalPage = ceil($count/$perpage);
			$page > $totalPage && $page = $totalPage;
			$result = $this->_getDs()->getUnActivedList($perpage, intval(($page - 1) * $perpage));
			/* @var $userDs PwUser */
			$userDs = app('user.PwUser');
			$list = $userDs->fetchUserByUid(array_keys($result), PwUser::FETCH_MAIN);
			$list = Utility::mergeArray($result, $list);
		}
		->with($count, 'count');
		->with($page, 'page');
		->with($perpage, 'perpage');
		->with(array('perpage' => $perpage), 'args');
		->with($list, 'list');
		
		// seo设置
		Wind::import('SRV:seo.bo.PwSeoBo');
		$seoBo = PwSeoBo::getInstance();
		$lang = Wind::getComponent('i18n');
		$seoBo->setCustomSeo($lang->getMessage('SEO:manage.user.email.title'), '', '');
		Core::setV('seo', $seoBo);
	}
	
	/** 
	 * 批量审核用户
	 *
	 */
	public function docheckAction(Request $request) {
		$uids = $request->get('uid', 'post');
		if (!$uids) return $this->showError('operate.select');
		/* @var $userDs PwUser */
		$userDs = app('user.PwUser');
		$infos = $userDs->fetchUserByUid($uids, PwUser::FETCH_MAIN);
		/* @var $groupService PwUserGroupsService */
		$groupService = app('usergroup.srv.PwUserGroupsService');
		$strategy = Core::C('site', 'upgradestrategy');
		$clearUid = array();
		foreach ($infos as $_temp) {
			$clearUid[] = $_temp['uid'];
			if (Tool::getstatus($_temp['status'], PwUser::STATUS_UNCHECK)) {
				$userDm = new PwUserInfoDm($_temp['uid']);
				$userDm->setUncheck(false);
				if (!Tool::getstatus($_temp['status'], PwUser::STATUS_UNACTIVE)) {
					$userDm->setGroupid(0);
					$_credit = $userDs->getUserByUid($_temp['uid'], PwUser::FETCH_DATA);
					$credit = $groupService->calculateCredit($strategy, $_credit);
					$memberid = $groupService->calculateLevel($credit, 'member');
					$userDm->setMemberid($memberid);
				}
				$userDs->editUser($userDm, PwUser::FETCH_MAIN);
			}
		}
		$this->_getDs()->batchCheckUser($clearUid);
		return $this->showMessage('operate.success');
	}
	
	/** 
	 * 批量激活用户
	 *
	 */
	public function doactiveAction(Request $request) {
		$uids = $request->get('uid', 'post');
		if (!$uids) return $this->showError('operate.select');
		/* @var $userDs PwUser */
		$userDs = app('user.PwUser');
		$infos = $userDs->fetchUserByUid($uids, PwUser::FETCH_MAIN);
		/* @var $groupService PwUserGroupsService */
		$groupService = app('usergroup.srv.PwUserGroupsService');
		$strategy = Core::C('site', 'upgradestrategy');
		$clearUid = array();
		foreach ($infos as $_temp) {
			$clearUid[] = $_temp['uid'];
			if (Tool::getstatus($_temp['status'], PwUser::STATUS_UNACTIVE)) {
				$userDm = new PwUserInfoDm($_temp['uid']);
				$userDm->setUnactive(false);
				if (!Tool::getstatus($_temp['status'], PwUser::STATUS_UNCHECK)) {
					$userDm->setGroupid(0);
					$_credit = $userDs->getUserByUid($_temp['uid'], PwUser::FETCH_DATA);
					$credit = $groupService->calculateCredit($strategy, $_credit);
					$memberid = $groupService->calculateLevel($credit, 'member');
					$userDm->setMemberid($memberid);
				}
				$userDs->editUser($userDm, PwUser::FETCH_MAIN);
			}
		}
		$this->_getDs()->batchActiveUser($clearUid);
		return $this->showMessage('operate.success');
	}
	
	/** 
	 * 删除用户 批量
	 */
	public function deleteAction(Request $request) {
		$uids = $request->get('uid', 'post');
		if (!$uids) return $this->showError('operate.select');
		/* @var $userDs PwUser */
		$userDs = app('user.PwUser');
		$userDs->batchDeleteUserByUid($uids);
		return $this->showMessage('operate.success');
	}
	
	/**
	 * 获得用户的状态DS
	 *
	 * @return PwUserRegisterCheck
	 */
	private function _getDs() {
		return app('user.PwUserRegisterCheck');
	}
}