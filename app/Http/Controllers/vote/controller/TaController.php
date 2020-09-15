<?php
defined('WEKIT_VERSION') || exit('Forbidden');

Wind::import('SRV:poll.srv.PwPollDisplay');
Wind::import('SRV:poll.srv.dataSource.PwFetchPollByOrder');

/**
 * 投票模型
 *
 * 1. run 权限入口
 * </code>
 * @author MingXing Sun <mingxing.sun@aliyun-inc.com> 2012-01-12
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.windframework.com
 * @version $Id: TaController.php 3219 2012-01-12 06:43:45Z mingxing.sun $
 * @package admin
 * @subpackage controller
 */

class TaController extends Controller{
	
	public $page = 1;
	public $perpage = 10;
	
	public function beforeAction($handlerAdapter) {
		parent::beforeAction($handlerAdapter);
		if (!$this->loginUser->isExists()) {
			return redirect('u/login/run', array('backurl' => 'vote/ta/run'));
		}
	}
	
	/**
	 * 我关注的人参与的投票
	 *
	 */
	public function run(){
		$page = $request->get('page');
 		$page = $page < 1 ? 1 : intval($page);
		list($start, $limit) = Tool::page2limit($page, $this->perpage);
		
		$followUids = $this->getFollowUids($this->loginUser->uid);
		
		$total = $this->_getPollVoterDs()->countByUids($followUids);
		$poll = $total ? $this->_getPollVoterDs()->fetchPollByUid($followUids, $limit, $start) : array();

		$pollInfo = $pollid = array();

		if ($total) {
			$pollid = array();
			foreach ($poll as $value) {
				$pollid[] = $value['poll_id'];
			}
			Wind::import('SRV:poll.srv.dataSource.PwFetchPollByPollid');
			$pollDisplay = new PwPollDisplay(new PwFetchPollByPollid($pollid, count($pollid), 0));
			$pollInfo = $this->_buildPoll($pollDisplay->gather());
		}

		$latestPollDisplay = new PwPollDisplay(new PwFetchPollByOrder(10, 0, array('created_time'=>'0')));
		$latestPoll = $latestPollDisplay->gather();
		
		->with($total, 'total');
		->with($pollInfo, 'pollInfo');
		->with($latestPoll, 'latestPoll');
		->with($page, 'page');
		->with($this->perpage, 'perpage');
		->with(
			array(
				'allowview' => $this->loginUser->getPermission('allow_view_vote'),
				'allowvote'=> $this->loginUser->getPermission('allow_participate_vote')
			)
		, 'pollGroup');
		
		if (!$total) {
			$num = 20;
			$uids = $this->_getRecommendService()->getRecommendAttention($this->loginUser->uid,$num);
			$recommend = $this->_getRecommendService()->buildUserInfo($this->loginUser->uid, $uids, $num);
			->with($recommend, 'recommend');
		}
		
		// seo设置
		Wind::import('SRV:seo.bo.PwSeoBo');
		$seoBo = PwSeoBo::getInstance();
		$lang = Wind::getComponent('i18n');
		$seoBo->setCustomSeo($lang->getMessage('SEO:vote.ta.run.title'), '', '');
		Core::setV('seo', $seoBo);
	}
	
	/**
	 * 我关注的人发起的投票
	 *
	 */
	public function createAction(Request $request){
		$page = $request->get('page');
 		$page = $page < 1 ? 1 : intval($page);
		list($start, $limit) = Tool::page2limit($page, $this->perpage);
		
		$followUids = $this->getFollowUids($this->loginUser->uid);
		
		$total = $this->_getPwPollDs()->countPollByUids($followUids);
		
		$pollInfo = array();
		
		if ($total) {
			Wind::import('SRV:poll.srv.dataSource.PwFetchPollByUids');
			$pollDisplay = new PwPollDisplay(new PwFetchPollByUids($followUids, $limit, $start));
			$pollInfo = $this->_buildPoll($pollDisplay->gather());
		}
		
		$latestPollDisplay = new PwPollDisplay(new PwFetchPollByOrder(10, 0, array('created_time'=>'0')));
		$latestPoll = $latestPollDisplay->gather();
		
		->with($total, 'total');
		->with($pollInfo, 'pollInfo');
		->with($latestPoll, 'latestPoll');
		->with($page, 'page');
		->with($this->perpage, 'perpage');
		->with(
			array(
				'allowview' => $this->loginUser->getPermission('allow_view_vote'),
				'allowvote'=> $this->loginUser->getPermission('allow_participate_vote')
			)
		, 'pollGroup');
		
		if (!$total) {
			$num = 20;
			$uids = $this->_getRecommendService()->getRecommendAttention($this->loginUser->uid,$num);
			$recommend = $this->_getRecommendService()->buildUserInfo($this->loginUser->uid, $uids, $num);
			->with($recommend, 'recommend');
		}
	}
	
	/**
	 * 获取我关注的用户ID
	 *
	 * return array
	 */
	public function getFollowUids($uid, $limit = 500){
		$reuslt = array();
		$follow = app('attention.PwAttention')->getFollows($uid, $limit);
		foreach($follow as $key => $value){
			$reuslt[] = $value['touid'];
		}
		return $reuslt;
	}
	
	private function _buildPoll($data) {
		$pollid = $myPollid = $reuslt = array();
		foreach ($data as $value) {
			$pollid[] = $value['poll_id'];
		}
		
		$loginUserPollids = $this->_getPollVoterDs()->getPollByUidAndPollid($this->loginUser->uid, $pollid);

		foreach ($data as $value) {
			$value['isvoted'] = in_array($value['poll_id'], $loginUserPollids)  ? true : false;
			$reuslt[] = $value;
		}

		return $reuslt;
	}
	
	/**
	 * 获取投票service服务层
	 *
	 * @return PwPollService
	 */
	protected function _getPwPollService(){
		return app('poll.srv.PwPollService');
	}

	/**
	 * 获取投票接口
	 *
	 * @return PwPoll
	 */
	protected function _getPwPollDs(){
		return app('poll.PwPoll');
	}
	
	/**
	 * get PwPollVoter
	 *
	 * @return PwPollVoter
	 */
	protected function _getPollVoterDs(){
		return app('poll.PwPollVoter');
	}
	
	/**
	 * get PwAttentionRecommendFriendsService
	 *
	 * @return PwAttentionRecommendFriendsService
	 */
	protected function _getRecommendService() {
		return app('attention.srv.PwAttentionRecommendFriendsService');
	}
}