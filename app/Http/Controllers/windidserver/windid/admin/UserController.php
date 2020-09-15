<?php
Wind::import('APPS:windid.admin.WindidBaseController');
/**
 * 后台用户管理界面
 *
 * @author xiaoxia.xu <xiaoxia.xuxx@aliyun-inc.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: UserController.php 24723 2013-02-17 09:14:43Z jieyin $
 * @package 
 */
class UserController extends WindidBaseController {
	
		
	private $pageNumber = 10;

	/* (non-PHPdoc)
	 * @see WindController::run()
	 */
	public function run() {
		/* @var $groupDs PwUserGroups */
		list($sName, $sUid, $sEmail, $page) = $request->get(array('username', 'uid', 'email',  'page'));
		Wind::import('WSRV:user.vo.WindidUserSo');
		$vo = new WindidUserSo();
		$sName && $vo->setUsername($sName);
		$sUid && $vo->setUid($sUid);
		$sEmail && $vo->setEmail($sEmail);
		
		$page = intval($page) == 0 ? 1 : abs(intval($page));
		/* @var $searchDs PwUserSearch */
		$searchDs = app('WSRV:user.WindidUser');
		$count = $searchDs->countSearchUser($vo);

		$result = array();
		if (0 < $count) {
			$totalPage = ceil($count/$this->pageNumber);
			$page > $totalPage && $page = $totalPage;
			list($start, $limit) = Tool::page2limit($page, $this->pageNumber);
			$result = $searchDs->searchUser($vo, $limit, $start);
		}
		$data = $vo->getData();
		->with($data, 'args');
		->with($page, 'page');
		->with($this->pageNumber, 'perPage');
		->with($count, 'count');
		->with($result, 'list');
	}

	/** 
	 * 添加用户
	 * 
	 * @return void
	 */
	public function addAction(Request $request) {
		if ($request->get('type', 'post') === 'do') {
			Wind::import('WSRV:user.dm.WindidUserDm');
			$dm = new WindidUserDm();
			$dm->setUsername($request->get('username', 'post'))
				->setPassword($request->get('password', 'post'))
			    ->setEmail($request->get('email', 'post'))
			    ->setRegdate(Tool::getTime())
				->setRegip($request->getClientIp());
				
			$result = app('WSRV:user.WindidUser')->addUser($dm);
			if ($result instanceof ErrorBag) {
				return $this->showError($result->getError());
			}

			app('WSRV:user.srv.WindidUserService')->defaultAvatar($result);
			$srv = app('WSRV:notify.srv.WindidNotifyService');
			$srv->send('addUser', array('uid' => $result));

			return $this->showMessage('WINDID:success');
		}
	}

	/** 
	 * 编辑用户信息
	 *
	 * @return void
	 */
	public function editAction(Request $request) {
		$uid = (int)$request->get('uid', 'get');
		$user = app('WSRV:user.WindidUser');
		$_info = $user->getUserByUid($uid, WindidUser::FETCH_ALL);
		if (!$_info) return $this->showError('WINDID:fail');
		$tYear = Tool::time2str(Tool::getTime(), 'Y');
		$birMin = $tYear-100;
		$birMax = $tYear + 100;
		->with($this->_buildArea($_info['location']), 'location');
		->with($this->_buildArea($_info['hometown']), 'hometown');
		->with($birMin . '-01-01', 'bmin');
		->with($birMax . '-12-31', 'bmax');
		->with($_info, 'info');
		->with($_info['online'] / 3600, 'online');
		->with($uid, 'uid');
	}

	/** 
	 * 编辑用户信息操作
	 * 
	 * @return voido
	 */
	public function doEditAction(Request $request) {
		$uid = (int)$request->get('uid', 'post');
		if (!$uid) return $this->showError('WINDID:fail');
		Wind::import('WSRV:user.dm.WindidUserDm');
		$dm = new WindidUserDm($uid);
		
		//用户信息
		$dm->setUsername($request->get('username', 'post'));
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
		/* @var $srv WindidAreaService */
		$srv = app('WSRV:area.srv.WindidAreaService');
		$areas = $srv->fetchAreaInfo(array($hometown, $location));
		$dm->setLocation($location, isset($areas[$location]) ? $areas[$location] : '');
		$dm->setHometown($hometown, isset($areas[$hometown]) ? $areas[$hometown] : '');
		$dm->setHomepage($request->get('homepage', 'post'));
		$dm->setProfile($request->get('profile', 'post'));
		
		//交易信息
		$dm->setAlipay($request->get('alipay', 'post'));
		$dm->setMobile($request->get('mobile', 'post'));
		
		
		//联系信息
		$dm->setEmail($request->get('email', 'post'));
		$dm->setAliww($request->get('aliww', 'post'));
		$dm->setQq($request->get('qq', 'post'));
		$dm->setMsn($request->get('msn', 'post'));
		
	
		$ds = app('WSRV:user.WindidUser');
		$result = $ds->editUser($dm);
		if ($result instanceof ErrorBag) {
			return $this->showError($result->getError());
		}
		$srv = app('WSRV:notify.srv.WindidNotifyService');
		$srv->send('editUser', array('uid' => $uid, 'changepwd' => $dm->password ? 1 : 0));

		return $this->showMessage('WINDID:success', 'windid/user/edit?uid=' . $uid);
	}

	
	/**
	 * 恢复系统头像
	 */
	public function defaultAvatarAction(Request $request) {
		$uid = (int)$request->get('uid', 'get');
		if (!$uid) return $this->showError('WINDID:fail');
		$api = app(AvatarApi::class);
		if ($api->defaultAvatar($uid) > 0) return $this->showMessage('success');
		return $this->showError('WINDID:fail');
	}
	
	/** 
	 * 清理用户信息
	 * 
	 * @return void
	 */
	public function deleteAction(Request $request) {
		$uid = $request->get('uid', 'get');
		if (!$uid) return $this->showError('WINDID:fail');
		$ds = app('WSRV:user.WindidUser');
		$ds->deleteUser($uid);

		$srv = app('WSRV:notify.srv.WindidNotifyService');
		$srv->send('deleteUser', array('uid' => $uid));

		return $this->showMessage('WINDID:success');
	}

	public function editCreditAction(Request $request) {
		$uid = $request->get('uid', 'get');
		if (!$uid) return $this->showError('WINDID:fail');
		//Wind::import('WSRV:user.dm.WindidUserDm');
		//$dm = new WindidUserDm($uid);
		
		$service = $this->_getConfigDs();
		$config = $service->getValues('credit');
		$user = app('WSRV:user.WindidUser');
		$info = $user->getUserByUid($uid, WindidUser::FETCH_DATA);
		if (!$info) return $this->showError('WINDID:fail');
		$userCreditDb = array();
		foreach ($config['credits'] AS  $k => $value) {
			if (isset($info['credit' . $k])) {
				$userCreditDb[$k] = array('name' => $value['name'], 'num' => $info['credit' . $k]);
			}
		}
		->with($uid, 'uid');
		->with($userCreditDb, 'credits');
	}
	
	/** 
	 * 设置用户积分
	 * 
	 * @return void
	 */
	public function doEditCreditAction(Request $request) {
		$uid = $request->get('uid', 'post');
		if (!$uid) return $this->showError('WINDID:fail');
		$credits = $request->get("credit");
		Wind::import('WSRV:user.dm.WindidCreditDm');
		$dm = new WindidCreditDm($uid);
		foreach ($credits as $id => $value) {
			$dm->setCredit($id, $value);
		}
		
		$ds = app('WSRV:user.WindidUser');
		$result = $ds->updateCredit($dm);
		if ($result instanceof WindidError) {
			return $this->showError($result->getCode());
		}
		$srv = app('WSRV:notify.srv.WindidNotifyService');
		$srv->send('editCredit', array('uid' => $uid));

		return $this->showMessage('WINDID:success', 'windid/user/editCredit?uid=' . $uid);
	}

	/**
	 * @return PwCreditSetService
	 */
	private function _getPwCreditService() {
		return app("credit.srv.PwCreditSetService");
	}
	
	private function _getConfigDs() {
		return app('WSRV:config.WindidConfig');
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
		/* @var $areaSrv WindidAreaService */
		$areaSrv = app('WSRV:area.srv.WindidAreaService');
		$rout = $areaSrv->getAreaRout($areaid);
		return Utility::mergeArray($default, $rout);
	}
}