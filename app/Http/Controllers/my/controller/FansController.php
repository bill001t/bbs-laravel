<?php

/**
 * 粉丝controller
 *
 * @author Jianmin Chen <sky_hold@163.com>
 * @license http://www.phpwind.com
 * @version $Id: FansController.php 23994 2013-01-18 03:51:46Z long.shi $
 * @package forum
 */

class FansController extends Controller{
	
	public function beforeAction($handlerAdapter) {
		parent::beforeAction($handlerAdapter);
		if (!$this->loginUser->isExists()) {
			return redirect('u/login/run',array('backurl' => 'my/fans/run'));
		}
		->with('fans', 'li');
    }

	public function run() {
		
		$page = intval($request->get('page'));
		$page < 1 && $page = 1;
		$perpage = 20;
		list($start, $limit) = Tool::page2limit($page, $perpage);
		
		$count = $this->loginUser->info['fans'];
		$fans = $this->_getDs()->getFans($this->loginUser->uid, $limit, $start);
		$uids = array_keys($fans);
		$follows = $this->_getDs()->fetchFollows($this->loginUser->uid, $uids);
		$userList = app('user.PwUser')->fetchUserByUid($uids, PwUser::FETCH_MAIN | PwUser::FETCH_DATA | PwUser::FETCH_INFO);
		->with(Utility::mergeArray($fans, $userList), 'fans');
		->with($follows, 'follows');

		->with($page, 'page');
		->with($perpage, 'perpage');
		->with($count, 'count');
		//->with($url, 'url');
		
		// seo设置
		Wind::import('SRV:seo.bo.PwSeoBo');
		$seoBo = PwSeoBo::getInstance();
		$lang = Wind::getComponent('i18n');
		$seoBo->setCustomSeo($lang->getMessage('SEO:bbs.fans.run.title'), '', '');
		Core::setV('seo', $seoBo);
	}
	
	/**
	 * PwAttention
	 * 
	 * @return PwAttention
	 */
	private function _getDs() {
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
}