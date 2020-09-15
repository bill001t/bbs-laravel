<?php
Wind::import('ADMIN:library.AdminBaseController');
Wind::import('SRV:user.vo.PwUserSo');
Wind::import('SRV:user.srv.PwClearUserService');

/**
 * 后台用户管理界面
 *
 * @author xiaoxia.xu <xiaoxia.xuxx@aliyun-inc.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: ManageController.php 24850 2013-02-25 02:20:12Z jieyin $
 * @package 
 */
class ManageController extends AdminBaseController {
	
	private $upgradeGroups = array('name' => '普通组', 'gid' => '0');
		
	private $pageNumber = 10;

	/* (non-PHPdoc)
	 * @see WindController::run()
	 */
	public function run() {
		/* @var $groupDs PwUserGroups */
		$groupDs = app('usergroup.PwUserGroups');
		$groups = $groupDs->getNonUpgradeGroups();
		$groups[0] = $this->upgradeGroups;
		ksort($groups);
		list($sName, $sUid, $sEmail, $sGroup, $page) = $request->get(array('username', 'uid', 'email', 'gid', 'page'));
		$vo = new PwUserSo();
		$sName && $vo->setUsername($sName);
		$sUid && $vo->setUid($sUid);
		$sEmail && $vo->setEmail($sEmail);
		(!$sGroup || in_array(-1, $sGroup)) || $vo->setGid($sGroup);
		$page = intval($page) == 0 ? 1 : abs(intval($page));
		/* @var $searchDs PwUserSearch */
		$searchDs = app('SRV:user.PwUserSearch');
		$count = $searchDs->countSearchUser($vo);

		$result = array();
		if (0 < $count) {
			$totalPage = ceil($count/$this->pageNumber);
			$page > $totalPage && $page = $totalPage;
			/* @var $searchDs PwUserSearch */
			$searchDs = app('user.PwUserSearch');
			list($start, $limit) = Tool::page2limit($page, $this->pageNumber);
			$result = $searchDs->searchUser($vo, $limit, $start);
			if ($result) {
				/* @var $userDs PwUser */
				$userDs = app('user.PwUser');
				$list = $userDs->fetchUserByUid(array_keys($result), PwUser::FETCH_DATA);
				$result = Utility::mergeArray($result, $list);
			}
		}
		$data = $vo->getData();
		(!$sGroup || in_array(-1, $sGroup)) && $data['gid'] = array(-1);
		->with($data, 'args');
		->with($page, 'page');
		->with($this->pageNumber, 'perPage');
		->with($count, 'count');
		->with($result, 'list');
		
		->with($groups, 'groups');
	}

	/** 
	 * 添加用户
	 * 
	 * @return void
	 */
	public function addAction(Request $request) {
		if ($request->get('type', 'post') === 'do') {
			Wind::import('SRC:service.user.dm.PwUserInfoDm');
			$dm = new PwUserInfoDm();
			$dm->setUsername($request->get('username', 'post'))
				->setPassword($request->get('password', 'post'))
			    ->setEmail($request->get('email', 'post'))
			    ->setRegdate(Tool::getTime())
				->setRegip($request->getClientIp());
			$groupid = $request->get('groupid', 'post');
			$dm->setGroupid($groupid);
			if ($groupid != 0) {
				// 默认组不保存到groups
				/* @var $groupDs PwUserGroups */
				$groupDs = app('usergroup.PwUserGroups');
				$groups = $groupDs->getGroupsByType('default');
				if (!in_array($groupid, array_keys($groups))) {
					$dm->setGroups(array($groupid => 0));
				}
			}
			/* @var $groupService PwUserGroupsService */
			$groupService = app('usergroup.srv.PwUserGroupsService');
			$memberid = $groupService->calculateLevel(0);
			$dm->setMemberid($memberid);
				
			$result = app('user.PwUser')->addUser($dm);
			if ($result instanceof ErrorBag) {
				return $this->showError($result->getError());
			}
			//添加站点统计信息
			Wind::import('SRV:site.dm.PwBbsinfoDm');
			$bbsDm = new PwBbsinfoDm();
			$bbsDm->setNewmember($dm->getField('username'))->addTotalmember(1);
			app('site.PwBbsinfo')->updateInfo($bbsDm);
			//app('user.srv.PwUserService')->restoreDefualtAvatar($result);
			return $this->showMessage('USER:add.success');
		}
		/* @var $groupDs PwUserGroups */
		$groupDs = app('usergroup.PwUserGroups');
		$groups = $groupDs->getClassifiedGroups();
		unset($groups['system'][5]);//排除“版主”
		$result = array_merge($groups['special'], $groups['system']);
		->with($result, 'groups');
	}

	/** 
	 * 编辑用户信息
	 *
	 * @return void
	 */
	public function editAction(Request $request) {
		$info = $this->checkUser();
		/* @var $pwUser PwUser */
		$pwUser = app('user.PwUser');
		$_info = $pwUser->getUserByUid($info['uid'], PwUser::FETCH_ALL);
		$_winfo = app(UserApi::class)->getUser($info['uid']);
		$_info['regip'] = $_winfo['regip'];
		
		$tYear = Tool::time2str(Tool::getTime(), 'Y');
		$birMin = $tYear-100;
		$birMax = $tYear + 100;
		->with($this->_buildArea($_info['location']), 'location');
		->with($this->_buildArea($_info['hometown']), 'hometown');
		->with($birMin . '-01-01', 'bmin');
		->with($birMax . '-12-31', 'bmax');
		->with($_info, 'info');
		->with(round($_info['onlinetime'] / 3600), 'online');
		
		//可能的扩展点
		$work = app('SRV:work.PwWork')->getByUid($info['uid']);
		$education = app('SRV:education.srv.PwEducationService')->getEducationByUid($info['uid'], 100);
		->with($work ,'workList');
		->with($education, 'educationList');
	}

	/** 
	 * 编辑用户信息操作
	 * 
	 * @return voido
	 */
	public function doEditAction(Request $request) {
		$info = $this->checkUser();
		
		Wind::import('SRC:service.user.dm.PwUserInfoDm');
		$dm = new PwUserInfoDm($info['uid']);
		
		//用户信息
		//$dm->setUsername($request->get('username', 'post'));
		list($password, $repassword) = $request->get(array('password', 'repassword'), 'post');
		if ($password) {
			if ($password != $repassword) return $this->showError('USER:user.error.-20');
			$dm->setPassword($password);
		}
		$dm->setEmail($request->get('email', 'post'));
		
		list($question, $answer) = $request->get(array('question', 'answer'), 'post');
		switch ($question) {
			case '-2':
			 	$dm->setQuestion('', '');
			 	break;
			case '-1':
			default :
				break;
		}

		$dm->setRegdate(Tool::str2time($request->get('regdate', 'post')));
		$dm->setRegip($request->get('regip', 'post'));
		$dm->setOnline(intval($request->get('online', 'post')) * 3600);
		
		//基本资料
		$dm->setRealname($request->get('realname', 'post'));
		$dm->setGender($request->get('gender', 'post'));
		$birthday = $request->get('birthday', 'post');
		if ($birthday) {
			$bir = explode('-', $birthday);
			isset($bir[0]) && $dm->setByear($bir[0]);
			isset($bir[1]) && $dm->setBmonth($bir[1]);
			isset($bir[2]) && $dm->setBday($bir[2]);
		} else {
			$dm->setBday('')->setByear('')->setBmonth('');
		}
		list($hometown, $location) = $request->get(array('hometown', 'location'), 'post');

		$srv = WindidApi::api('area');
		$areas = $srv->fetchAreaInfo(array($hometown, $location));
		$dm->setLocation($location, isset($areas[$location]) ? $areas[$location] : '');
		$dm->setHometown($hometown, isset($areas[$hometown]) ? $areas[$hometown] : '');
		$dm->setHomepage($request->get('homepage', 'post'));
		$dm->setProfile($request->get('profile', 'post'));
		
		//交易信息
		$dm->setAlipay($request->get('alipay', 'post'));
		$dm->setMobile($request->get('mobile', 'post'));
		$dm->setTelphone($request->get('telphone', 'post'));
		$dm->setAddress($request->get('address', 'post'));
		$dm->setZipcode($request->get('zipcode', 'post'));
		
		//联系信息
		$dm->setEmail($request->get('email', 'post'));
		$dm->setAliww($request->get('aliww', 'post'));
		$dm->setQq($request->get('qq', 'post'));
		$dm->setMsn($request->get('msn', 'post'));
		
		/* @var $pwUser PwUser */
		$pwUser = app('user.PwUser');
		$result = $pwUser->editUser($dm);
		if ($result instanceof ErrorBag) {
			return $this->showError($result->getError());
		}
		$isFounder = $this->isFounder($info['username']);
		return $this->showMessage($isFounder ? 'USER:founder.update.success' : 'USER:update.success', 'u/manage/edit?uid=' . $info['uid']);
	}

	/** 
	 * 编辑用户积分
	 * 
	 * @return void
	 */
	public function editCreditAction(Request $request) {
		$info = $this->checkUser();
		/* @var $pwUser PwUser */
		$pwUser = app('user.PwUser');
		$userCredits = $pwUser->getUserByUid($info['uid'], PwUser::FETCH_DATA);
		$userCreditDb = array();
		
		Wind::import('SRV:credit.bo.PwCreditBo');
		/* @var $pwCreditBo PwCreditBo */
		$pwCreditBo = PwCreditBo::getInstance();
		
		foreach ($pwCreditBo->cType as $k => $value) {
			if (isset($userCredits['credit' . $k])) {
				$userCreditDb[$k] = array('name' => $value, 'num' => $userCredits['credit' . $k]);
			}
		}
		->with($userCreditDb, 'credits');
	}

	/** 
	 * 设置用户积分
	 * 
	 * @return void
	 */
	public function doEditCreditAction(Request $request) {
		$info = $this->checkUser();
		$credits = $request->get("credit");
		/* @var $pwUser PwUser */
		$pwUser = app('user.PwUser');
		$userCredits = $pwUser->getUserByUid($info['uid'], PwUser::FETCH_DATA);
		$changes = array();
		foreach ($credits as $id => $value) {
			$org = isset($userCredits['credit' . $id]) ? $userCredits['credit' . $id] : 0;
			$changes[$id] = $value - $org;
		}
		
		Wind::import('SRV:credit.bo.PwCreditBo');
		/* @var $creditBo PwCreditBo */
		$creditBo = PwCreditBo::getInstance();
		$creditBo->addLog('admin_set', $changes, new PwUserBo($this->loginUser->uid));
		$creditBo->execute(array($info['uid'] => $credits), false);
		return $this->showMessage('USER:update.success', 'u/manage/editCredit?uid=' . $info['uid']);
	}

	/** 
	 * 设置用户组
	 * 
	 * @return void
	 */
	public function editGroupAction(Request $request) {
		$info = $this->checkUser();

		/* @var $groupDs PwUserGroups */
		$groupDs = app('usergroup.PwUserGroups');

		/* @var $groups 将包含有特殊组和管理组 */
		$systemGroups = $groupDs->getClassifiedGroups();
		$groups = array();
		foreach (array('system','special','default') as $k) {
			foreach ($systemGroups[$k] as $gid => $_item) {
				if (in_array($gid, array(1, 2))) continue;
				$groups[$gid] = $_item;
			}
		}

		/* @var $belongDs PwUserBelong */
		$belongDs = app('user.PwUserBelong');
		$userGroups = $belongDs->getUserBelongs($info['uid']);
		
		->with(array_keys($systemGroups['default']), 'defaultGroups');
		->with($userGroups, 'userGroups');
		->with($groups, 'allGroups');
		->with($info, 'info');
	}

	/** 
	 * 操作用户组设置
	 * 
	 * @return void
	 */
	public function doEditGroupAction(Request $request) {

		$info = $this->checkUser();
		list($groupid, $groups, $endtime) = $request->get(array('groupid', 'groups', 'endtime'), 'post');
		/* @var $groupDs PwUserGroups */
		$groupDs = app('usergroup.PwUserGroups');
		$banGids = array_keys($groupDs->getGroupsByType('default'));//默认用户组
		$clearGids = array();
		
		//如果用户原先的用户组是在默认组中，则该用户组不允许被修改
		if (in_array($info['groupid'], $banGids) && $info['groupid'] != $groupid) {
			switch($info['groupid']) {
				case 6:
					return $this->showError('USER:user.belong.delban.error');
					break;
				case 7:
					return $this->showError('USER:user.belong.delactive.error');
					break;
				default :
					return $this->showError('USER:user.belong.default.error');
					break;
			}
		}
		//如果用户原先的用户组是不在默认组中，新设置的用户组在默认组中，则抛错
		if (!in_array($info['groupid'], $banGids) && in_array($groupid, $banGids) && $info['groupid'] != $groupid) {
			switch($groupid) {
				case 6:
					return $this->showError('USER:user.belong.ban.error');
					break;
				case 7:
					return $this->showError('USER:user.belong.active.error');
					break;
				default :
					return $this->showError('USER:user.belong.default.error');
					break;
			}
		}
		
		if (($if = in_array($groupid, $banGids)) || ($r = array_intersect($banGids, $groups))) {
			return $this->showError('USER:user.belong.default.error');
// 			(!$if && $r) && $groupid = array_shift($r);
		} else {
			foreach ($groups as $value) {
				$clearGids[$value] = (isset($endtime[$value]) && $endtime[$value]) ? Tool::str2time($endtime[$value]) : 0;
			}
			if ($groupid == 0) {
				/* @var $userService PwUserService */
				$userService = app('user.srv.PwUserService');
				list($groupid, $clearGids) = $userService->caculateUserGroupid($groupid, $clearGids);
			} elseif (!isset($clearGids[$groupid])) {
				$clearGids[$groupid] = 0;
			}
		}

		$oldGid = explode(',', $info['groups']);
		$info['groupid'] && array_push($oldGid, $info['groupid']);
		//总版主处理
		if (in_array(5, $oldGid) && !isset($clearGids[5])) {
			return $this->showError('USER:user.forumadmin.delete.error');
		}
		if (!in_array(5, $oldGid) && isset($clearGids[5])) {
			return $this->showError('USER:user.forumadmin.add.error');
		}

		Wind::import('SRV:user.dm.PwUserInfoDm');
		$dm = new PwUserInfoDm($info['uid']);
		$dm->setGroupid($groupid)
			->setGroups($clearGids);

		/* @var $userDs PwUser */
		$userDs = app('user.PwUser');
		$result = $userDs->editUser($dm, PwUser::FETCH_MAIN);
		if ($result instanceof ErrorBag) {
			return $this->showError($result->getError());
		}
		
		/* if (in_array($groupid, $banGids)) {
			app('SRV:forum.srv.PwForumMiscService')->updateDataByUser($info['username']);
		} */
		
		return $this->showMessage('USER:update.success', 'u/manage/editGroup?uid=' . $info['uid']);
	}

	/**
	 * 恢复系统头像
	 */
	public function defaultAvatarAction(Request $request) {
		$info = $this->checkUser();
		$p = app('user.srv.PwUserService')->restoreDefualtAvatar($info['uid']);
		if ($p === false) {
			return $this->showError('operate.fail');
		}
		return $this->showMessage('success');
	}
	
	/** 
	 * 清理用户信息
	 * 
	 * @return void
	 */
	public function clearAction(Request $request) {
		$info = $this->checkUser();
		/* @var $userSer PwClearUserService */
		$userSer = app('user.srv.PwClearUserService');
		->with($userSer->getClearTypes(), 'types');
	}
	
	/** 
	 * 清理用户操作
	 * 
	 * @return void
	 */
	public function doClearAction(Request $request) {
		$info = $this->checkUser();
		/* @var $userSer PwClearUserService */
		$userSer = new PwClearUserService($info['uid'], new PwUserBo($this->loginUser->uid));
		if (($result = $userSer->run($request->get('clear', 'post'))) instanceof ErrorBag) {
			return $this->showError($result->getError(), 'admin/u/manage/run');
		}
		return $this->showMessage('USER:clear.success', 'admin/u/manage/run');
	}

	/** 
	 * 检查用户信息同时返回用户对象
	 *
	 * @return PwUserBo
	 */
	private function checkUser() {
		$uid = $request->get('uid');
		if ($uid <= 0) return redirect('admin/u/manage/run');
		/* @var $pwUser PwUser */
		$pwUser = app('user.PwUser');
		$info = $pwUser->getUserByUid($uid);
		if (!$info) return $this->showError('USER:illega.id', 'admin/u/manage/run');
		->with($uid, 'uid');
		->with($info['username'], 'username');
		return $info;
	}

	/**
	 * @return PwCreditSetService
	 */
	private function _getPwCreditService() {
		return app("credit.srv.PwCreditSetService");
	}

	/**
	 * 设置地区显示
	 * 
	 * @return array
	 */
	private function _buildArea($areaid) {
		$default = array(array('areaid' => '', 'name' => ''), array('areaid' => '', 'name' => ''), array('areaid' => '', 'name' => ''));
		if (!$areaid) {
			return $default;
		}
		$rout = WindidApi::api('area')->getAreaRout($areaid);
		return Utility::mergeArray($default, $rout);
	}
}