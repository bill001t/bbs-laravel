<?php
defined('WEKIT_VERSION') || exit('Forbidden');

/**
 * 投票基础业务处理
 *
 * @author MingXing Sun <mingxing.sun@aliyun-inc.com> 2012-01-12
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.windframework.com
 * @version $Id: IndexController.php 3219 2012-01-12 06:43:45Z mingxing.sun $
 * @package modules
 * @subpackage controller
 */

class IndexController extends Controller{
	
	public $page = 1;
	public $perpage = 20;
		
	/**
	 * 查看投票参与人员
	 *
	 * @return void
	 */
	public function memberAction(Request $request){
		if (!$this->loginUser->getPermission('allow_view_vote')) return $this->showError('VOTE:group.not.allow.view');
		
		list($pollid, $optionid) = $request->get(array('pollid', 'optionid'), 'get');
		
		$poll = $this->_getPollService()->getPoll($pollid);
		if (!$poll) return $this->showError('VOTE:thread.not.exist');
		
		//$isVoted = $this->_getPollVoterDs()->isVoted($this->loginUser->uid, $pollid);
		//$allowView = (!$poll['isafter_view'] || $isVoted);
		//if (!$allowView) return $this->showError('VOTE:not.allow.view');
		
		$page = $request->get('page');
		$page > 1 && $this->page = $page;
		list($start, $limit) = Tool::page2limit($this->page, $this->perpage);
		
		$total = $this->_getPollVoterDs()->countUserByOptionid($optionid);
		$vote = $total ? $this->_getPollVoterDs()->getUserByOptionid($optionid, $limit, $start) : array();
		
		$uids = $userName = array();
		foreach($vote as $value){
			$uids[] = $value['uid'];
		}
		
		$userList = $uids ? $this->_getUserDs()->fetchUserByUid($uids) : array();
		foreach ($userList as $value) {
			$userName[$value['uid']] = $value['username'];
		}
	
		$this->_getPollService()->resetOptionVotedNum($optionid);
		
		->with(array('data' => $userName));
		return $this->showMessage('success');
	}
	
	public function deloptionAction(Request $request) {
		list($pollid, $optionid) = $request->get(array('pollid', 'optionid'));
		$pollid = intval($pollid);
		$optionid = intval($optionid);
		if (!$pollid || !$optionid) return $this->showError('VOTE:fail');
		
		$poll = $this->_getPollService()->getPoll($pollid);
		if (!$poll) return $this->showError('VOTE:thread.not.exist');
		
		if ($poll['voter_num'] || $this->loginUser->uid != $poll['created_userid']) return $this->showError('VOTE:options.not.allow.delete');
		
		$pollOptionDs = app('poll.PwPollOption'); /* @var $pollOptionDs PwPollOption */

//		$optionTotal = $pollOptionDs->countByPollid($pollid);
//		if ($optionTotal < 3) return $this->showError('VOTE:options.default.option.num');
		
		$option = $pollOptionDs->get($optionid);
		$pollOptionDs->delete($optionid);
		$option['image'] && $this->_getPollService()->removeImg($option['image']);
		
		$this->_afterDelete($pollid);

		return $this->showMessage('success');
	}
	
	public function deloptionimgAction(Request $request) {
		list($pollid, $optionid) = $request->get(array('pollid', 'optionid'));
		$pollid = intval($pollid);
		$optionid = intval($optionid);
		if (!$pollid || !$optionid) return $this->showError('VOTE:fail');
		
		$poll = $this->_getPollService()->getPoll($pollid);
		if (!$poll) return $this->showError('VOTE:thread.not.exist');
		
		if ($poll['voter_num'] || $this->loginUser->uid != $poll['created_userid']) return $this->showError('VOTE:options.not.allow.delete');
		
		$pollOptionDs = app('poll.PwPollOption'); /* @var $pollOptionDs PwPollOption */
		$option = $pollOptionDs->get($optionid);

		Wind::import('SRV:poll.dm.PwPollOptionDm');
		$dm = new PwPollOptionDm($optionid);
		$dm->setImage('');
		$this->_getPollOptionDS()->update($dm);
//		$optionTotal = $pollOptionDs->countByPollid($pollid);
//		if ($optionTotal < 3) return $this->showError('VOTE:options.default.option.num');
		$option['image'] && $this->_getPollService()->removeImg($option['image']);

		$this->_afterDelete($pollid);

		return $this->showMessage('success');
	}

	private function _afterDelete($pollid) {
		$optionList = $this->_getPollOptionDS()->getByPollid($pollid);
		if (!$optionList) return false;

		$flag = false;
		foreach ($optionList as $value) {
			if (!$value['image']) continue;
			$flag = true;
		}

		Wind::import('SRV:poll.dm.PwPollDm');
		$dm = new PwPollDm($pollid);
		$dm->setIsIncludeImg($flag ? 1 : 0);
		$this->_getPollDs()->updatePoll($dm);

		return true;
	}

	/**
	 * get PwPoll
	 *
	 * @return PwPoll
	 */
	protected function _getPollDs() {
		return app('poll.PwPoll');
	}

	/**
	 * get PwPollOption
	 * 
	 * @return PwPollOption
	 */
	private function _getPollOptionDS() {
		return app('poll.PwPollOption');
	}

	/**
	 * get PwPollService
	 *
	 * @return PwPollService
	 */
	protected function _getPollService(){
		return app('poll.srv.PwPollService');
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
	 * get PwUser
	 *
	 * @return PwUser
	 */
	protected function _getUserDs(){
		return app('user.PwUser');
	}
}