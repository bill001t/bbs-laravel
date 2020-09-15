<?php
defined('WEKIT_VERSION') || exit('Forbidden');

/**
 * Enter description here ...
 *
 * @author jinlong.panjl <jinlong.panjl@aliyun-inc.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id$
 * @package wind
 */
Wind::import('ADMIN:library.AdminBaseController');
Wind::import('SRV:forum.srv.operation.PwDeleteTopic');
Wind::import('SRV:forum.srv.operation.PwDeleteReply');
Wind::import('SRV:forum.srv.dataSource.PwFetchTopicByTid');
Wind::import('SRV:forum.srv.dataSource.PwFetchReplyByPid');

class ArticleController extends AdminBaseController {
	
	private $perpage = 20;

	public function run() {
		$fid = '';
		->with($this->_getFroumService()->getForumOption($fid), 'option_html');
		return view('article_searchthread');
	}

	public function threadadvancedAction(Request $request) {
		$fid = '';
		->with($this->_getFroumService()->getForumOption($fid), 'option_html');
		return view('article_searchthread_advanced');
	}
	
	public function searchthreadAction(Request $request) {
		list($page, $perpage, $keyword, $created_username, $time_start, $time_end, $fid, $digest, $created_userid, $created_ip, $hits_start, $hits_end, $replies_start, $replies_end) = $request->get(array('page', 'perpage', 'keyword', 'created_username', 'time_start', 'time_end', 'fid', 'digest', 'created_userid', 'created_ip', 'hits_start', 'hits_end', 'replies_start', 'replies_end'));
		if ($created_username) {
			$user = $this->_getUserDs()->getUserByName($created_username);
			if (!$user) return $this->showError(array('USER:exists.not', array('{username}' => $created_username)));
			if ($created_userid) {
				($created_userid != $user['uid']) && return $this->showError('USER:username.notequal.uid');
			}
			$created_userid = $user['uid'];
		}
		// dm条件
		Wind::import('SRV:forum.vo.PwThreadSo');
		$dm = new PwThreadSo();
		$keyword && $dm->setKeywordOfTitleOrContent($keyword);
		if ($fid) {
			$forum = app('forum.PwForum')->getForum($fid);
			if ($forum['type'] != 'category') {
				$dm->setFid($fid);
			} else {
				$srv = app('forum.srv.PwForumService');
				$fids = array(0);
				$forums = $srv->getForumsByLevel($fid, $srv->getForumMap());
				foreach ($forums as $value) {
					$fids[] = $value['fid'];
				}
				$dm->setFid($fids);
			}
		}
		$created_userid && $dm->setAuthorId($created_userid);
		$time_start && $dm->setCreateTimeStart(Tool::str2time($time_start));
		$time_end && $dm->setCreateTimeEnd(Tool::str2time($time_end));
		$digest && $dm->setDigest($digest);
		$hits_start && $dm->setHitsStart($hits_start);
		$hits_end && $dm->setHitsEnd($hits_end);
		$replies_start && $dm->setRepliesStart($replies_start);
		$replies_end && $dm->setRepliesEnd($replies_end);
		$created_ip && $dm->setCreatedIp($created_ip);
		$dm->setDisabled(0)->orderbyCreatedTime(false);
		$count = $this->_getThreadDs()->countSearchThread($dm);
		if ($count) {
			$page = $page ? $page : 1;
			$perpage = $perpage ? $perpage : $this->perpage;
			list($start, $limit) = Tool::page2limit($page, $perpage);
			$threads = $this->_getThreadDs()->searchThread($dm,$limit,$start);
		}
		->with($count, 'count');
		->with($page, 'page');
		->with($perpage, 'perpage');
		->with(array(
			'keyword' => $keyword, 
			'created_username' => $created_username, 
			'time_start' => $time_start, 
			'time_end' => $time_end, 
			'fid' => $fid, 
			'digest' => $digest, 
			'created_userid' => $created_userid, 
			'created_ip' => $created_ip, 
			'hits_start' => $hits_start,
			'hits_end' => $hits_end, 
			'replies_start' => $replies_start, 
			'replies_end' => $replies_end,
		), 'args');
		
		->with($this->_getFroumService()->getForumList($fid), 'forumList');
		->with($this->_getFroumService()->getForumOption($fid), 'option_html');
		->with($threads, 'threads');
	}
	
	public function removeAction(Request $request) {
		
	}
	
	public function deletethreadAction(Request $request) {
		$isDeductCredit = $request->get('isDeductCredit');
		$tids = $request->get('tids', 'post');
		if (!is_array($tids) || !count($tids)) {
			return $this->showError('operate.select');
		}
		$service = new PwDeleteTopic(new PwFetchTopicByTid($tids), new PwUserBo($this->loginUser->uid));
		$service->setRecycle(true)->setIsDeductCredit((bool)$isDeductCredit)->execute();
				
		return $this->showMessage('operate.success');
	}
	
	public function replylistAction(Request $request) {
		$fid = '';
		->with($this->_getFroumService()->getForumOption($fid), 'option_html');
		return view('article_searchreply');
	}
	
	public function replyadvancedAction(Request $request) {
		$fid = '';
		->with($this->_getFroumService()->getForumOption($fid), 'option_html');
		return view('article_searchreply_advanced');
	}
	
	public function searchreplyAction(Request $request) {
		list($page, $perpage, $keyword, $fid, $created_username, $created_time_start, $created_time_end, $created_userid, $created_ip, $tid) = $request->get(array('page', 'perpage', 'keyword', 'fid', 'created_username', 'created_time_start', 'created_time_end', 'created_userid', 'created_ip', 'tid'));
		if ($created_username) {
			$user = $this->_getUserDs()->getUserByName($created_username);
			if (!$user) return $this->showError('USER:username.empty');
			if ($created_userid) {
				($created_userid != $user['uid']) && return $this->showError('USER:username.notequal.uid');
			}
			$created_userid = $user['uid'];
		}
		// dm条件
		Wind::import('SRV:forum.vo.PwPostSo');
		$dm = new PwPostSo();
		$dm->setDisabled(0)->orderbyCreatedTime(false);
		$keyword && $dm->setKeywordOfTitleOrContent($keyword);
		if ($fid) {
			$forum = app('forum.PwForum')->getForum($fid);
			if ($forum['type'] != 'category') {
				$dm->setFid($fid);
			} else {
				$srv = app('forum.srv.PwForumService');
				$fids = array(0);
				$forums = $srv->getForumsByLevel($fid, $srv->getForumMap());
				foreach ($forums as $value) {
					$fids[] = $value['fid'];
				}
				$dm->setFid($fids);
			}
		}
		$created_userid && $dm->setAuthorId($created_userid);
		$created_time_start && $dm->setCreateTimeStart(Tool::str2time($created_time_start));
		$created_time_end && $dm->setCreateTimeEnd(Tool::str2time($created_time_end));
		$tid && $dm->setTid($tid);
		$created_ip && $dm->setCreatedIp($created_ip);
		
		$count = $this->_getThreadDs()->countSearchPost($dm);
		if ($count) {
			$page = $page ? $page : 1;
			$perpage = $perpage ? $perpage : $this->perpage;
			list($start, $limit) = Tool::page2limit($page, $perpage);
			$posts = $this->_getThreadDs()->searchPost($dm,$limit,$start);
		}
		->with($count, 'count');
		->with($page, 'page');
		->with($perpage, 'perpage');
		->with(array(
			'keyword' => $keyword, 
			'created_username' => $created_username, 
			'created_time_start' => $created_time_start, 
			'created_time_end' => $created_time_end, 
			'fid' => $fid, 
			'created_userid' => $created_userid, 
			'created_ip' => $created_ip, 
			'tid' => $tid,
		), 'args');
		
		->with($this->_getFroumService()->getForumList($fid), 'forumList');
		->with($this->_getFroumService()->getForumOption($fid), 'option_html');
		->with($posts, 'posts');
	}
	
	/**
	 * Enter description here ...
	 *
	 */
	public function deletereplyAction(Request $request) {
		$isDeductCredit = $request->get('isDeductCredit');
		$pids = $request->get('pids', 'post');
		if (!is_array($pids) || !count($pids)) {
			return $this->showError('operate.select');
		}
		$service = new PwDeleteReply(new PwFetchReplyByPid($pids), new PwUserBo($this->loginUser->uid));
		$service->setRecycle(true)->setIsDeductCredit((bool)$isDeductCredit)->execute();
		return $this->showMessage('operate.success');
	}

	/**
	 * Enter description here ...
	 *
	 * @return PwThread
	 */
	private function _getThreadDs(){
		return app('forum.PwThread');
	}
	
	private function _getUserDs(){
		return app('user.PwUser');
	}
	
	protected function _getFroumService() {
		return app('forum.srv.PwForumService');
	}
}
?>