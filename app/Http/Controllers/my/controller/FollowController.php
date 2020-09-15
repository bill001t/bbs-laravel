<?php

Wind::import('SRV:attention.PwAttentionType');

/**
 * 首页
 *
 * @author Jianmin Chen <sky_hold@163.com>
 * @license http://www.phpwind.com
 * @version $Id: FollowController.php 28843 2013-05-28 01:57:37Z jieyin $
 * @package forum
 */

class FollowController extends Controller{
	
	/* (non-PHPdoc)
	 * @see PwBaseController::beforeAction()
	 */
	public function beforeAction($handlerAdapter) {
		parent::beforeAction($handlerAdapter);
		if (!$this->loginUser->isExists()) {
			return redirect('u/login/run',array('backurl' => 'my/follow/run'));
		}
		->with('follow', 'li');
    }

	/**
	 * 关注-首页
	 */
	public function run() {
		
		$type = $request->get('type');
		$page = intval($request->get('page'));
		$page < 1 && $page = 1;
		$perpage = 20;
		list($start, $limit) = Tool::page2limit($page, $perpage);
		$url = $classCurrent = array();
		
		$typeCounts = $this->_getTypeDs()->countUserType($this->loginUser->uid);
		if ($type) {
			$tmp = $this->_getTypeDs()->getUserByType($this->loginUser->uid, $type, $limit, $start);
			$follows = $this->_getDs()->fetchFollows($this->loginUser->uid, array_keys($tmp));
			$count = $typeCounts[$type] ? $typeCounts[$type]['count'] : 0;
			$url['type'] = $type;
			$classCurrent[$type] = 'current';
		} else {
			$follows = $this->_getDs()->getFollows($this->loginUser->uid, $limit, $start);
			$count = $this->loginUser->info['follows'];
			$classCurrent[0] = 'current';
		}
		$uids = array_keys($follows);
		$fans = $this->_getDs()->fetchFans($this->loginUser->uid, $uids);
		$userList = app('user.PwUser')->fetchUserByUid($uids, PwUser::FETCH_MAIN | PwUser::FETCH_DATA | PwUser::FETCH_INFO);

		$service = $this->_getService();
		$typeArr = $service->getAllType($this->loginUser->uid);
		$userType = $service->getUserType($this->loginUser->uid, $uids);
		foreach ($userType as $key => $value) {
			$tmp = array();
			foreach ($value as $k => $v) {
				$tmp[$v] = $typeArr[$v];
			}
			ksort($tmp);
			$userType[$key] = $tmp;
		}
		$follows = Utility::mergeArray($follows, $userList);
		if (!$type && !$follows) {
			$num = 30;
			$uids = $this->_getRecommendService()->getOnlneUids($num);
			$uids = array_slice($uids, 0, 24);
			->with($this->_getRecommendService()->buildUserInfo($this->loginUser->uid, $uids, $num), 'recommend');
		}
		
		->with($follows, 'follows');
		->with($typeArr, 'typeArr');
		->with($type, 'type');
		->with($userType, 'userType');
		->with($typeCounts, 'typeCounts');
		->with($fans, 'fans');
		->with($classCurrent, 'classCurrent');

		->with($page, 'page');
		->with($perpage, 'perpage');
		->with($count, 'count');
		->with($url, 'url');
		
		// seo设置
		Wind::import('SRV:seo.bo.PwSeoBo');
		$seoBo = PwSeoBo::getInstance();
		$lang = Wind::getComponent('i18n');
		$seoBo->setCustomSeo($lang->getMessage('SEO:bbs.follow.run.title'), '', '');
		Core::setV('seo', $seoBo);
	}
	
	/**
	 * 关注用户
	 */
	public function addAction(Request $request) {
		$uid = $request->get('uid', 'post');
		if (!$uid) {
			return $this->showError('operate.select');
		}
		$private = app('user.PwUserBlack')->checkUserBlack($this->loginUser->uid, $uid);
		if ($private) {
			return $this->showError('USER:attention.private.black');
		}
		$result = $this->_getService()->addFollow($this->loginUser->uid, $uid);
		
		if ($result instanceof ErrorBag) {
			return $this->showError($result->getError());
		}
		return $this->showMessage('success', 'my/follow/run');
	}
	
	/**
	 * 批量关注用户
	 */
	public function batchaddAction(Request $request) {
		$uids = $request->get('uids','post');
		if (!$uids) return $this->showError('USER:attention.uid.empty');
		foreach ($uids as $uid) {
			$private = app('user.PwUserBlack')->checkUserBlack($this->loginUser->uid, $uid);
			if ($private) {
				if (count($uids) == 1) {
					return $this->showError('USER:attention.private.black');
				}
				continue;
			}
			$this->_getService()->addFollow($this->loginUser->uid, $uid);
		}
		return $this->showMessage('success', 'my/follow/run');
	}
	
	/**
	 * 取消关注
	 */
	public function deleteAction(Request $request) {
		$uid = $request->get('uid');
		if (!$uid) {
			return $this->showError('operate.select');
		}
		$result = $this->_getService()->deleteFollow($this->loginUser->uid, $uid);
		if ($result instanceof ErrorBag) {
			return $this->showError($result->getError());
		}
		return $this->showMessage('success', 'my/follow/run');
	}

	/**
	 * 添加关注分类
	 */
	public function addtypeAction(Request $request) {
		$name = $request->get('name', 'post');
		$uid = (int)$request->get('uid');
		if (!$name) {
			return $this->showError('operate.select');
		}
		$result = $this->_getService()->addType($this->loginUser->uid, $name);

		if ($result instanceof ErrorBag) {
			return $this->showError($result->getError());
		}
		if ($uid) {
			$this->_getTypeDs()->addUserType($this->loginUser->uid, $uid, $result);
		}
		->with(array('id' => $result, 'name' => $name), 'data');
		return $this->showMessage('success');
	}
	
	/**
	 * 保存用户分类
	 */
	public function savetypeAction(Request $request) {
		list($uid, $id, $type) = $request->get(array('uid', 'id', 'type'), 'post');
		if (!$uid) {
			return $this->showError('operate.select');
		}
		if ($type == 1) {
			$this->_getTypeDs()->addUserType($this->loginUser->uid, $uid, $id);
		} else {
			$this->_getTypeDs()->deleteByUidAndTouidAndType($this->loginUser->uid, $uid, $id);
		}
		return $this->showMessage('success');
	}
	
	/**
	 * 修改关注分类
	 */
	public function editTypeAction(Request $request) {
		list($id, $name) = $request->get(array('id', 'name'), 'post');
		if (!$id) {
			return $this->showError('operate.select');
		}
		$type = $this->_getTypeDs()->getType($id);
		if (empty($type) || $type['uid'] != $this->loginUser->uid) {
			return $this->showError('USER:attention.type.edit.self');
		}
		
		$types = $this->_getService()->getAllType($this->loginUser->uid);
		if (count($types) > 20) {
			return $this->showError('USER:attention.type.count.error');
		}
		unset($types[$id]);
		if (in_array($name, $types)) {
			return $this->showError('USER:attention.type.repeat');
		}

		$result = $this->_getTypeDs()->editType($id, $name);
		if ($result instanceof ErrorBag) {
			return $this->showError($result->getError());
		}
		->with(array('id' => $id, 'name' => $name), 'data');
		return $this->showMessage('success');
	}
	
	/**
	 * 删除关注分类
	 */
	public function deleteTypeAction(Request $request) {
		$id = $request->get('id', 'post');
		if (!$id) {
			return $this->showError('operate.select');
		}
		$type = $this->_getTypeDs()->getType($id);
		if (empty($type) || $type['uid'] != $this->loginUser->uid) {
			return $this->showError('USER:attention.type.delete.self');
		}
		$this->_getTypeDs()->deleteType($id);
		return $this->showMessage('success');
	}
	
	public function samefriendAction(Request $request) {
		$uid = (int)$request->get('uid');
		$result = $this->_getRecommendFriendsDs()->getSameUser($this->loginUser->uid, $uid);
		$sameUser = $result['recommend_user'] ? unserialize($result['recommend_user']) : array();
		$sameUser['sameUser'] = $sameUser['sameUser'] ? array_slice($sameUser['sameUser'], 0, 3) : array();
		->with($sameUser, 'sameUser');
		return view('TPL:my.recommend_same_user');
	}
	
	public function recommendfriendAction(Request $request) {
		->with($this->loginUser, 'loginUser');
		return view('TPL:my.recommend_mod_user');
	}
	
	public static function bulidGroup($group) {
		$str = implode('', $group);
		$str = trim($str);
		if (Tool::strlen($str) <= 5) {
			return implode(',', $group);
		}
		$i = 0;
		$t = array();
		foreach ($group as $value) {
			$value = trim($value);
			$len = Tool::strlen($value);
			if ($i + $len < 5) {
				$t[] = $value;
			} else {
				$t[] = Tool::substrs($value, 5 - $i, 0, false);
				break;
			}
			$i += $len;
		}
		return implode(',', $t) . '...';
	}

	public static function bulidUserType($userType) {
		foreach ($userType as $k => $v) {
			$_tmp['id'] = $k;
			$items = array();
			foreach ($v as $tk => $tv) {
				$items[] = array('id' => $tk, 'value' => $tv);
			}
			$_tmp['items'] = $items;
			$array[] = $_tmp;
			
		}
		return $array;
	}
	
 	/**
 	 * PwUser
 	 *
 	 * @return PwUser
 	 */
	protected function _getUser() {
		return app('user.PwUser');
	}
	
	/**
	 * PwAttention
	 *
	 * @return PwAttention
	 */
	protected function _getDs() {
		return app('attention.PwAttention');
	}
	
	/**
	 * PwAttentionType
	 *
	 * @return PwAttentionType
	 */
	protected function _getTypeDs() {
		return app('attention.PwAttentionType');
	}
	
	/**
	 * PwAttentionService
	 *
	 * @return PwAttentionService
	 */
	protected function _getService() {
		return app('attention.srv.PwAttentionService');
	}
	
	/**
	 * Enter description here ...
	 * 
	 * @return PwAttentionRecommendFriends
	 */
	private function _getRecommendFriendsDs(){
		return app('attention.PwAttentionRecommendFriends');
	}
	
	/**
	 * PwAttentionRecommendFriendsService
	 *
	 * @return PwAttentionRecommendFriendsService
	 */
	protected function _getRecommendService() {
		return app('attention.srv.PwAttentionRecommendFriendsService');
	}
}