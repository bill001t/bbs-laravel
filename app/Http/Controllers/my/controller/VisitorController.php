<?php

/**
 * 访问脚印
 *
 * @author jinlong.panjl <jinlong.panjl@aliyun-inc.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: VisitorController.php 23994 2013-01-18 03:51:46Z long.shi $
 * @package wind
 */
class VisitorController extends Controller{
	
	public function beforeAction($handlerAdapter) {
		parent::beforeAction($handlerAdapter);
		if (!$this->loginUser->isExists()) {
			return redirect('u/login/run',array('backurl' => 'my/visitor/run'));
		}
		->with('visitor', 'li');
    }
	
	/**
	 * 谁看过我
	 */
	public function run() {
		$space = $this->_getSpaceDs()->getSpace($this->loginUser->uid);
		$visitors = $space['visitors'] ? unserialize($space['visitors']) : array();
		$uids = array_keys($visitors);
		if ($uids) {
			$userList = app('user.PwUser')->fetchUserByUid($uids, PwUser::FETCH_MAIN | PwUser::FETCH_DATA | PwUser::FETCH_INFO);
			$userList = $this->_buildData($userList, $uids);
			$follows = $this->_getAttentionDs()->fetchFollows($this->loginUser->uid, $uids);
			$fans = $this->_getAttentionDs()->fetchFans($this->loginUser->uid, $uids);
			$friends = array_intersect_key($fans, $follows);
			->with($fans, 'fans');
			->with($friends, 'friends');
			->with($userList, 'userList');
			->with($follows, 'follows');
		} else {
			Wind::import('SRV:user.vo.PwUserSo');
			$vo = new PwUserSo();
			$vo->orderbyLastpost(false);
			$lastPostUser = app('SRV:user.PwUserSearch')->searchUser($vo, 2);
			if ($lastPostUser) {
				unset($lastPostUser[$this->loginUser->uid]);
				$lastPostUser = array_keys($lastPostUser);
				->with($lastPostUser[0], 'lastPostUser');
			}
		}
		->with($visitors, 'visitors');
		
		// seo设置
		Wind::import('SRV:seo.bo.PwSeoBo');
		$seoBo = PwSeoBo::getInstance();
		$lang = Wind::getComponent('i18n');
		$seoBo->setCustomSeo($lang->getMessage('SEO:bbs.visitor.run.title'), '', '');
		Core::setV('seo', $seoBo);
	}
	
	/**
	 * 我看过谁
	 */
	public function tovisitAction(Request $request) {
		$space = $this->_getSpaceDs()->getSpace($this->loginUser->uid);
		$visitors = $space['tovisitors'] ? unserialize($space['tovisitors']) : array();
		$uids = array_keys($visitors);
		if ($uids) {
			$userList = app('user.PwUser')->fetchUserByUid($uids, PwUser::FETCH_MAIN | PwUser::FETCH_DATA | PwUser::FETCH_INFO);
			$userList = $this->_buildData($userList, $uids);
			$follows = $this->_getAttentionDs()->fetchFollows($this->loginUser->uid, $uids);
			$fans = $this->_getAttentionDs()->fetchFans($this->loginUser->uid, $uids);
			$friends = array_intersect_key($fans, $follows);
			->with($friends, 'friends');
			->with($userList, 'userList');
			->with($follows, 'follows');
			->with($fans, 'fans');
		} else {
			Wind::import('SRV:user.vo.PwUserSo');
			$vo = new PwUserSo();
			$vo->orderbyLastpost(false);
			$lastPostUser = app('SRV:user.PwUserSearch')->searchUser($vo, 2);
			if ($lastPostUser) {
				unset($lastPostUser[$this->loginUser->uid]);
				$lastPostUser = array_keys($lastPostUser);
				->with($lastPostUser[0], 'lastPostUser');
			}
		}
		->with($visitors, 'visitors');
	}
	
	private function _buildData($data,$keys) {
		$temp = array();
		foreach ($keys as $v) {
			$temp[$v] = $data[$v];
		}
		return $temp;
	}
	
	/**
	 * PwAttention
	 * 
	 * @return PwAttention
	 */
	private function _getAttentionDs() {
		return app('attention.PwAttention');
	}
	
 	/**
 	 * PwSpace
 	 *
 	 * @return PwSpace
 	 */
 	private function _getSpaceDs() {
 		return app('space.PwSpace');
 	}
}