<?php
defined('WEKIT_VERSION') || exit('Forbidden');

/**
 * 用户投票处理层
 *
 * @author MingXing Sun <mingxing.sun@aliyun-inc.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: VoteController.php 24134 2013-01-22 06:19:24Z xiaoxia.xuxx $
 * @package poll
 */

class VoteController extends Controller{

	public function beforeAction($handlerAdapter) {
		parent::beforeAction($handlerAdapter);
		if (!$this->loginUser->isExists()) return $this->showError('VOTE:user.not.login');
	}
	
	public function run() {
		if (!$this->loginUser->getPermission('allow_participate_vote')) return $this->showError('VOTE:group.not.allow.participate');
		
		list($appType, $typeid, $optionid) = $request->get(array('apptype', 'typeid', 'optionid'));
		if (empty($optionid) || !is_array($optionid)) return $this->showError('VOTE:not.select.option');

		$poll = $this->_serviceFactory($appType, $typeid);

		if ( ($result = $poll->check()) !== true) {
			return $this->showError($result->getError());
		}
		
		if (!$poll->isInit()) return $this->showError('VOTE:thread.not.exist');
		if ($poll->isExpired()) return $this->showError('VOTE:vote.activity.end');
		$regtimeLimit = $poll->getRegtimeLimit();
		if ($regtimeLimit && $this->loginUser->info['regdate']  > $regtimeLimit) return $this->showError(array('VOTE:vote.regtime.limit', array('{regtimelimit}'=> pw::time2str($regtimeLimit, 'Y-m-d'))));

		if ( ($result = $this->_getPollService()->doVote($this->loginUser->uid, $poll->info['poll_id'], $optionid)) !== true) {
			return $this->showError($result->getError());
		}
		
		return $this->showMessage('VOTE:vote.success');
	}
	
	public function forumlistAction(Request $request) {
		$forums = app('forum.PwForum')->getForumList(PwForum::FETCH_ALL);
		$service = app('forum.srv.PwForumService');
		$map = $service->getForumMap();
		$cate = array();
		$forum = array();
		foreach ($map[0] as $key => $value) {
			if (!$value['isshow']) continue;
			$array = $service->findOptionInMap($value['fid'], $map, array('sub' => '--', 'sub2' => '----'));
			$tmp = array();
		
			foreach ($array as $k => $v) {
				$forumset = $forums[$k]['settings_basic'] ? unserialize($forums[$k]['settings_basic']) : array();
				$isAllowPoll = isset($forumset['allowtype']) && is_array($forumset['allowtype']) && in_array('poll', $forumset['allowtype']);
				 
				if ($forums[$k]['isshow'] && $isAllowPoll && (!$forums[$k]['allow_post'] || $this->loginUser->inGroup(explode(',', $forums[$k]['allow_post'])))) {
					$tmp[$k] = strip_tags($v);
				}
			}
			
			if ($tmp) {
				$cate[$value['fid']] = $value['name'];
				$forum[$value['fid']] = $tmp;
			}
		}
		
		$response = array(
			'cate' => $cate,
			'forum' => $forum
		);
		
		->with(Tool::jsonEncode($response), 'data');
		return $this->showMessage('success');
	}
	
	private function _serviceFactory($appType, $typeid) {
		switch ($appType) {
			case '0' : 
				Wind::import('SRV:poll.bo.PwThreadPollBo');
				$bo =  new PwThreadPollBo($typeid);
				break;
	
			default:
				Wind::import('SRV:poll.bo.PwThreadPollBo');
				$bo =  new PwThreadPollBo($typeid);
				break;
		}
		
		return $bo;
	}
	
	/**
	 * get PwPollService
	 *
	 * @return PwPollService
	 */
	private function _getPollService() {
		return app('poll.srv.PwPollService');
	}
}