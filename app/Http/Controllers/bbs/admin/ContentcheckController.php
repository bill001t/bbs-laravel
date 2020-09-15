<?php
defined('WEKIT_VERSION') || exit('Forbidden');

Wind::import('ADMIN:library.AdminBaseController');

/**
 * 帖子审核管理
 *
 * @author Jianmin Chen <sky_hold@163.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: ContentcheckController.php 27729 2013-04-28 02:00:50Z jieyin $
 * @package forum
 */

class ContentcheckController extends AdminBaseController {

	public function run() {
		
		$page = intval($request->get('page'));
		list($author, $fid, $createdTimeStart, $createdTimeEnd) = $request->get(array('author', 'fid', 'created_time_start', 'created_time_end'));

		$page < 1 && $page = 1;
		$perpage = 20;
		list($start, $limit) = Tool::page2limit($page, $perpage);

		Wind::import('SRV:forum.vo.PwThreadSo');
		$so = new PwThreadSo();
		$so->setDisabled(1)->orderbyCreatedTime(0);
		
		if ($author) {
			$so->setAuthor($author);
		}
		if ($fid) {
			$forum = app('forum.PwForum')->getForum($fid);
			if ($forum['type'] != 'category') {
				$so->setFid($fid);
			} else {
				$srv = app('forum.srv.PwForumService');
				$fids = array(0);
				$forums = $srv->getForumsByLevel($fid, $srv->getForumMap());
				foreach ($forums as $value) {
					$fids[] = $value['fid'];
				}
				$so->setFid($fids);
			}
		}
		if ($createdTimeStart) {
			$so->setCreateTimeStart(Tool::str2time($createdTimeStart));
		}
		if ($createdTimeEnd) {
			$so->setCreateTimeEnd(Tool::str2time($createdTimeEnd));
		}

		$count = app('forum.PwThread')->countSearchThread($so);
		$threaddb = app('forum.PwThread')->searchThread($so, $limit, $start, PwThread::FETCH_ALL);

		->with($threaddb, 'threadb');
		->with(app('forum.srv.PwForumService')->getForumList($fid), 'forumlist');
		->with(app('forum.srv.PwForumService')->getForumOption($fid), 'option_html');
		->with(array(
			'author' => $author, 
			'created_time_start' => $createdTimeStart, 
			'created_time_end' => $createdTimeEnd, 
			'fid' => $fid, 
		), 'args');
		->with($page, 'page');
		->with($perpage, 'perpage');
		->with($count, 'count');
	}
	
	public function doPassThreadAction(Request $request) {

		$tid = $request->get('tid', 'post');
		if (empty($tid)) {
			return $this->showError('operate.select');
		}
		!is_array($tid) && $tid = array($tid);
		
		Wind::import('SRV:forum.srv.dataSource.PwFetchTopicByTid');
		Wind::import('SRV:forum.srv.operation.PwPassTopic');

		$service = new PwPassTopic(new PwFetchTopicByTid($tid));
		$service->execute();

		return $this->showMessage('success');
	}

	public function doDeleteThreadAction(Request $request) {

		$tid = $request->get('tid', 'post');
		if (empty($tid)) {
			return $this->showError('operate.select');
		}
		!is_array($tid) && $tid = array($tid);

		Wind::import('SRV:forum.srv.operation.PwDeleteTopic');
		Wind::import('SRV:forum.srv.dataSource.PwFetchTopicByTid');
		$deleteTopic = new PwDeleteTopic(new PwFetchTopicByTid($tid), new PwUserBo($this->loginUser->uid));
		$deleteTopic->setIsDeductCredit(1)->execute();

		return $this->showMessage('success');
	}

	public function replyAction(Request $request) {
		
		$page = intval($request->get('page'));
		list($author, $fid, $createdTimeStart, $createdTimeEnd) = $request->get(array('author', 'fid', 'created_time_start', 'created_time_end'));

		$page < 1 && $page = 1;
		$perpage = 20;
		list($start, $limit) = Tool::page2limit($page, $perpage);

		Wind::import('SRV:forum.vo.PwPostSo');
		$so = new PwPostSo();
		$so->setDisabled(1)->orderbyCreatedTime(0);
		$args = array();
		
		if ($author) {
			$so->setAuthor($author);
			$args['author'] = $author;
		}
		if ($fid) {
			$forum = app('forum.PwForum')->getForum($fid);
			if ($forum['type'] != 'category') {
				$so->setFid($fid);
			} else {
				$srv = app('forum.srv.PwForumService');
				$fids = array(0);
				$forums = $srv->getForumsByLevel($fid, $srv->getForumMap());
				foreach ($forums as $value) {
					$fids[] = $value['fid'];
				}
				$so->setFid($fids);
			}
		}
		if ($createdTimeStart) {
			$so->setCreateTimeStart(Tool::str2time($createdTimeStart));
			$args['created_time_start'] = $createdTimeStart;
		}
		if ($createdTimeEnd) {
			$so->setCreateTimeEnd(Tool::str2time($createdTimeEnd));
			$args['created_time_end'] = $createdTimeEnd;
		}

		$count = app('forum.PwThread')->countSearchPost($so);
		$postdb = app('forum.PwThread')->searchPost($so, $limit, $start);

		->with($postdb, 'postdb');
		->with(app('forum.srv.PwForumService')->getForumList($fid), 'forumlist');
		->with(app('forum.srv.PwForumService')->getForumOption($fid), 'option_html');

		->with($page, 'page');
		->with($perpage, 'perpage');
		->with($count, 'count');
		->with($args, 'args');
	}

	public function doPassPostAction(Request $request) {

		$pid = $request->get('pid', 'post');
		if (empty($pid)) {
			return $this->showError('operate.select');
		}
		!is_array($pid) && $pid = array($pid);
		
		Wind::import('SRV:forum.srv.dataSource.PwFetchReplyByPid');
		Wind::import('SRV:forum.srv.operation.PwPassReply');

		$service = new PwPassReply(new PwFetchReplyByPid($pid));
		$service->execute();

		return $this->showMessage('success');
	}

	public function doDeletePostAction(Request $request) {

		$pid = $request->get('pid', 'post');
		if (empty($pid)) {
			return $this->showError('operate.select');
		}
		!is_array($pid) && $pid = array($pid);

		Wind::import('SRV:forum.srv.operation.PwDeleteReply');
		Wind::import('SRV:forum.srv.dataSource.PwFetchReplyByPid');
		$deleteReply = new PwDeleteReply(new PwFetchReplyByPid($pid), PwUserBo::getInstance($this->loginUser->uid));
		$deleteReply->setIsDeductCredit(1)->execute();

		return $this->showMessage('success');
	}
}
?>