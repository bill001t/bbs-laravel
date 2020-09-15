<?php
Wind::import('APPS:space.controller.SpaceBaseController');
/**
 * the last known user to change this file in the repository <$LastChangedBy$>
 * 
 * @author $Author$ Foxsee@aliyun.com
 * @copyright ?2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id$
 * @package
 *
 */
class FollowsController extends SpaceBaseController {

	/**
	 * 关注-首页
	 */
	public function run() {
		$type = $request->get('type');
		$page = intval($request->get('page'));
		$page < 1 && $page = 1;
		$perpage = 20;
		list($start, $limit) = Tool::page2limit($page, $perpage);
		$args = $classCurrent = array();
/*		$typeCounts = $this->_getTypeDs()->countUserType($this->space->spaceUid);
		if ($type) {
			$tmp = $this->_getTypeDs()->getUserByType($this->space->spaceUid, $type, $limit, $start);
			$follows = $this->_getDs()->fetchFollows($this->space->spaceUid, array_keys($tmp));
			$count = $typeCounts[$type] ? $typeCounts[$type]['count'] : 0;
			$classCurrent[$type] = 'current';
			$args = array('type' => $type);
		} else {*/
		$follows = $this->_getDs()->getFollows($this->space->spaceUid, $limit, $start);
		$count = $this->space->spaceUser['follows'];
		$classCurrent[0] = 'current';
		//}
		$uids = array_keys($follows);
		$fans = $this->_getDs()->fetchFans($this->loginUser->uid, $uids);
		$myfollows = $this->_getDs()->fetchFollows($this->loginUser->uid, $uids);
		$userList = app('user.PwUser')->fetchUserByUid($uids, PwUser::FETCH_ALL);
		
		$service = $this->_getService();

		$args['uid'] = $this->space->spaceUid;
		$follows = Utility::mergeArray($follows, $userList);
		if (!$follows && $this->space->tome == PwSpaceBo::MYSELF) {
			$num = 20;
			$uids = $this->_getRecommendService()->getRecommendAttention($this->loginUser->uid,$num);
			->with($this->_getRecommendService()->buildUserInfo($this->loginUser->uid, $uids, $num), 'recommend');
		}
		->with($fans, 'fans');
		->with($follows, 'follows');
		->with($myfollows, 'myfollows');
		->with($classCurrent, 'classCurrent');
		->with($args, 'args');
		->with($page, 'page');
		->with($perpage, 'perpage');
		->with($count, 'count');
		->with('follows', 'src');
		
		// seo设置
		Wind::import('SRV:seo.bo.PwSeoBo');
		$seoBo = PwSeoBo::getInstance();
		$lang = Wind::getComponent('i18n');
		$seoBo->setCustomSeo(
			$lang->getMessage('SEO:space.follows.run.title', 
				array($this->space->spaceUser['username'], $this->space->space['space_name'])), '', 
			$lang->getMessage('SEO:space.follows.run.description', 
				array($this->space->spaceUser['username'])));
		Core::setV('seo', $seoBo);
	}

	protected function _getDs() {
		return app('attention.PwAttention');
	}

	protected function _getTypeDs() {
		return app('attention.PwAttentionType');
	}

	protected function _getService() {
		return app('attention.srv.PwAttentionService');
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

?>