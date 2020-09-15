<?php

/**
 * 找人Controller
 *
 * @author jinlong.panjl <jinlong.panjl@aliyun-inc.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: FriendController.php 23994 2013-01-18 03:51:46Z long.shi $
 * @package wind
 */
class FriendController extends Controller{
	
	private $_fetchNum = 100;
	
	public function beforeAction($handlerAdapter) {
		parent::beforeAction($handlerAdapter);
		if (!$this->loginUser->isExists()) {
			return redirect('u/login/run',array('backurl' => 'my/friend/run'));
		}
		->with('friend', 'li');
    }
	
	/** 
	 * 推荐关注
	 */
	public function run() {
		$uids = $this->getOnlneUids(40);
		$userList = $this->_buildUserInfo($this->loginUser->uid, $uids, 20);
		->with($userList, 'userList');
		
		// seo设置
		Wind::import('SRV:seo.bo.PwSeoBo');
		$seoBo = PwSeoBo::getInstance();
		$lang = Wind::getComponent('i18n');
		$seoBo->setCustomSeo($lang->getMessage('SEO:bbs.friend.run.title'), '', '');
		Core::setV('seo', $seoBo);
	}
	
	/** 
	 * 可能认识
	 */
	public function friendAction(Request $request) {
		$uids = $this->getOnlneUids(40);
		$userList = $this->_buildUserInfo($this->loginUser->uid, $uids, 20);
		->with($userList, 'userList');
	}
	
	/** 
	 * 搜索用户
	 */
	public function searchAction(Request $request) {
		list($username,$usertag) = $request->get(array('username','usertag'));
		$page = intval($request->get('page'));
		$username = trim($username);
		$usertag = trim($usertag);
		$page < 1 && $page = 1;
		$perpage = 20;
		list($start, $limit) = Tool::page2limit($page, $perpage);
		
		$usertags = $this->_getUserTagService()->getUserTagList($this->loginUser->uid);
		!$usertags && $hotTags = $this->_getUserTagDs()->getHotTag(10);
		$args = array();
		if ($username) {
			// 按用户名搜索
			Wind::import('SRV:user.vo.PwUserSo');
			$vo = new PwUserSo();
			$vo->setUsername($username);
			$searchDs = app('SRV:user.PwUserSearch');
			$count = $searchDs->countSearchUser($vo);
			if ($count) {
				$users = $searchDs->searchUser($vo, $limit, $start);
				$uids = array_keys($users);
			}
			$args['username'] = $username;
		}
		if ($usertag) {
			// 按用户标签搜索
			$tagInfo = $this->_getUserTagDs()->getTagByName($usertag);
			if ($tagInfo) {
				$count = $this->_getUserTagRelationDs()->countRelationByTagid($tagInfo['tag_id']);
				$tagRelations = $this->_getUserTagRelationDs()->getRelationByTagid($tagInfo['tag_id'], $limit, $start);
				$uids = array();
				foreach ($tagRelations as $v) {
					$uids[] = $v['uid'];
				}
			}
			$args['usertag'] = $usertag;
		}
		if ($uids) {
			$userList = app('user.PwUser')->fetchUserByUid($uids, PwUser::FETCH_MAIN | PwUser::FETCH_DATA | PwUser::FETCH_INFO);
			$follows = $this->_getAttentionDs()->fetchFollows($this->loginUser->uid, $uids);
			$fans = $this->_getAttentionDs()->fetchFans($this->loginUser->uid, $uids);
			$friends = array_intersect_key($fans, $follows);
			
			->with($fans, 'fans');
			->with($friends, 'friends');
			->with($userList, 'userList');
			->with($follows, 'follows');
		}
		
		->with($page, 'page');
		->with($perpage, 'perpage');
		->with($count, 'count');
		->with($args, 'args');
		->with($hotTags, 'hotTags');
		->with($usertags, 'usertags');
	}
	
	private function getOnlneUids($num) {
		$onlineUser = app('online.PwUserOnline')->getInfoList('',0,$num);
		return array_keys($onlineUser);
	}
	
	/** 
	 * 组装用户数据
	 * 
	 * @param int $uid
	 * @param array $uids
	 * @param int $num
	 * @return array
	 */
	private function _buildUserInfo($uid,$uids,$num) {
		$attentions = $this->_getAttentionDs()->fetchFollows($uid,$uids);
		$uids = array_diff($uids,array($uid),array_keys($attentions));
		$uids = array_slice($uids, 0, $num);
		return $this->_getUserDs()->fetchUserByUid($uids, PwUser::FETCH_MAIN | PwUser::FETCH_DATA | PwUser::FETCH_INFO);
	}
	
 	/**
 	 * PwUserDs
 	 *
 	 * @return PwUser
 	 */
 	private function _getUserDs() {
 		return app('user.PwUser');
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
	 * PwAttentionRecommendFriendsService
	 *
	 * @return PwAttentionRecommendFriendsService
	 */
	protected function _getRecommendService() {
		return app('attention.srv.PwAttentionRecommendFriendsService');
	}
 	
	/**
	 * PwUserTag
	 *
	 * @return PwUserTag
	 */
	private function _getUserTagDs() {
		return app('usertag.PwUserTag');
	}
	
	/**
	 * PwUserTagRelation
	 *
	 * @return PwUserTagRelation
	 */
	private function _getUserTagRelationDs() {
		return app('usertag.PwUserTagRelation');
	}
	
	/**
	 * PwUserTagService
	 *
	 * @return PwUserTagService
	 */
	private function _getUserTagService() {
		return app('usertag.srv.PwUserTagService');
	}

}