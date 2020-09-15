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
class FansController extends SpaceBaseController {

	public function run() {
		$page = intval($request->get('page'));
		$page < 1 && $page = 1;
		$perpage = 20;
		list($start, $limit) = Tool::page2limit($page, $perpage);
		
		$fans = $this->_getDs()->getFans($this->space->spaceUid, $limit, $start);
		$uids = array_keys($fans);
		$count = $this->space->spaceUser['fans'];
		$follows = $this->_getDs()->fetchFollows($this->loginUser->uid, $uids);
		$userList = app('user.PwUser')->fetchUserByUid($uids, PwUser::FETCH_ALL);

		->with(Utility::mergeArray($fans, $userList), 'fans');
		->with($follows, 'follows');
		->with($page, 'page');
		->with($perpage, 'perpage');
		->with($count, 'count');
		->with('fans', 'src');
		
		// seo设置
		Wind::import('SRV:seo.bo.PwSeoBo');
		$seoBo = PwSeoBo::getInstance();
		$lang = Wind::getComponent('i18n');
		$seoBo->setCustomSeo(
			$lang->getMessage('SEO:space.fans.run.title', 
				array($this->space->spaceUser['username'], $this->space->space['space_name'])), '', 
			$lang->getMessage('SEO:space.fans.run.description', 
				array($this->space->spaceUser['username'])));
		Core::setV('seo', $seoBo);
	}

	/**
	 * Enter description here .
	 * ..
	 *
	 * @return PwAttention
	 */
	private function _getDs() {
		return app('attention.PwAttention');
	}

	protected function _getRecommendService() {
		return app('attention.srv.PwAttentionRecommendFriendsService');
	}
}

?>