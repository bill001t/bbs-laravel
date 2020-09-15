<?php
Wind::import('APPS:manage.controller.BaseManageController');

/**
 * 帖子审核管理
 *
 * @author Jianmin Chen <sky_hold@163.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: ContentController.php 28815 2013-05-24 09:39:50Z jieyin $
 * @package forum
 */

class ContentController extends BaseManageController {
	
	/* (non-PHPdoc)
	 * @see BaseManageController::beforeAction()
	 */
	public function beforeAction($handlerAdapter) {
		parent::beforeAction($handlerAdapter);
		$result = $this->loginUser->getPermission('panel_bbs_manage', false, array());
		if (!$result['thread_check']) {
			return $this->showError('BBS:manage.thread_check.right.error');
		}
	}
	
	public function run() {
		
		$page = intval($request->get('page'));
		list($author, $fid, $createdTimeStart, $createdTimeEnd) = $request->get(array('author', 'fid', 'created_time_start', 'created_time_end'));

		$page < 1 && $page = 1;
		$perpage = 20;
		list($start, $limit) = Tool::page2limit($page, $perpage);

		Wind::import('SRV:forum.vo.PwThreadSo');
		$so = new PwThreadSo();
		$so->setDisabled(1)->orderbyCreatedTime(0);
		$url = array();
		
		if ($author) {
			$so->setAuthor($author);
			$url['author'] = $author;
		}
		if ($fid) {
			$so->setFid($fid);
			$url['fid'] = $fid;
		}
		if ($createdTimeStart) {
			$so->setCreateTimeStart(Tool::str2time($createdTimeStart));
			$url['created_time_start'] = $createdTimeStart;
		}
		if ($createdTimeEnd) {
			$so->setCreateTimeEnd(Tool::str2time($createdTimeEnd));
			$url['created_time_end'] = $createdTimeEnd;
		}

		$count = app('forum.PwThread')->countSearchThread($so);
		$threaddb = app('forum.PwThread')->searchThread($so, $limit, $start);
		->with($threaddb, 'threadb');
		->with(app('forum.srv.PwForumService')->getForumOption($fid), 'option_html');

		->with($page, 'page');
		->with($perpage, 'perpage');
		->with($count, 'count');
		->with($url, 'url');
		
		// seo设置
		Wind::import('SRV:seo.bo.PwSeoBo');
		$seoBo = PwSeoBo::getInstance();
		$lang = Wind::getComponent('i18n');
		$seoBo->setCustomSeo($lang->getMessage('SEO:manage.content.run.title'), '', '');
		Core::setV('seo', $seoBo);
	}
	
	public function doPassThreadAction(Request $request) {

		$tid = $request->get('tid', 'post');
		if (empty($tid)) {
			return $this->showError('operate.select');
		}
		!is_array($tid) && $tid = array($tid);

		$fids = array();
		$threaddb = app('forum.PwThread')->fetchThread($tid);
		foreach ($threaddb as $key => $value) {
			$fids[$value['fid']]++;
		}
		Wind::import('SRV:forum.dm.PwTopicDm');
		$dm = new PwTopicDm(true);
		$dm->setDisabled(0);
		app('forum.PwThread')->batchUpdateThread($tid, $dm, PwThread::FETCH_MAIN);

		foreach ($fids as $fid => $value) {
			app('forum.srv.PwForumService')->updateStatistics($fid, $value, 0, $value);
		}

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
		$url = array();
		
		if ($author) {
			$so->setAuthor($author);
			$url['author'] = $author;
		}
		if ($fid) {
			$so->setFid($fid);
			$url['fid'] = $fid;
		}
		if ($createdTimeStart) {
			$so->setCreateTimeStart(Tool::str2time($createdTimeStart));
			$url['created_time_start'] = $createdTimeStart;
		}
		if ($createdTimeEnd) {
			$so->setCreateTimeEnd(Tool::str2time($createdTimeEnd));
			$url['created_time_end'] = $createdTimeEnd;
		}

		$count = app('forum.PwThread')->countSearchPost($so);
		$postdb = app('forum.PwThread')->searchPost($so, $limit, $start);

		->with($postdb, 'postdb');
		
		->with(app('forum.srv.PwForumService')->getForumOption($fid), 'option_html');

		->with($page, 'page');
		->with($perpage, 'perpage');
		->with($count, 'count');
		->with($url, 'url');
		
		// seo设置
		Wind::import('SRV:seo.bo.PwSeoBo');
		$seoBo = PwSeoBo::getInstance();
		$lang = Wind::getComponent('i18n');
		$seoBo->setCustomSeo($lang->getMessage('SEO:manage.content.reply.title'), '', '');
		Core::setV('seo', $seoBo);
	}

	public function doPassPostAction(Request $request) {

		$pid = $request->get('pid', 'post');
		if (empty($pid)) {
			return $this->showError('operate.select');
		}
		!is_array($pid) && $pid = array($pid);

		$fids = $tids = array();
		$postdb = app('forum.PwThread')->fetchPost($pid);
		foreach ($postdb as $key => $value) {
			$fids[$value['fid']]++;
			$tids[$value['tid']]++;
		}
		
		Wind::import('SRV:forum.dm.PwReplyDm');
		Wind::import('SRV:forum.dm.PwTopicDm');
		$dm = new PwReplyDm(true);
		$dm->setDisabled(0);
		app('forum.PwThread')->batchUpdatePost($pid, $dm);
		
		foreach ($tids as $key => $value) {
			$post = current(app('forum.PwThread')->getPostByTid($key, 1, 0, false));
			$dm = new PwTopicDm($key);
			$dm->addReplies($value);
			$dm->setLastpost($post['created_userid'], $post['created_username'], $post['created_time']);
			app('forum.PwThread')->updateThread($dm, PwThread::FETCH_MAIN);
		}
		foreach ($fids as $fid => $value) {
			app('forum.srv.PwForumService')->updateStatistics($fid, 0, $value, $value);
		}

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