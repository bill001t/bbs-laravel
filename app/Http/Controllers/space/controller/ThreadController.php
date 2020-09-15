<?php
Wind::import('APPS:space.controller.SpaceBaseController');
/**
 * the last known user to change this file in the repository  <$LastChangedBy: long.shi $>
 * @author  Foxsee@aliyun.com
 * @copyright ?2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: ThreadController.php 23994 2013-01-18 03:51:46Z long.shi $
 * @package
 */
class ThreadController extends SpaceBaseController {
	
	/**
	 * 我的帖子
	 *
	 */
	public function run() {
		Wind::import('SRV:forum.srv.PwThreadList');
		list($page, $perpage) = $request->get(array('page', 'perpage'));
		!$perpage && $perpage = 20;
		$threadList = new PwThreadList();
		$threadList->setPage($page)->setPerpage($perpage);
		$dataSource = null;
		if ($this->space->spaceUid == $this->loginUser->uid) {
			Wind::import('SRV:forum.srv.threadList.PwMyThread');
			$dataSource = new PwMyThread($this->space->spaceUid);
		} else {
			Wind::import('SRV:forum.srv.threadList.PwSpaceThread');
			$dataSource = new PwSpaceThread($this->space->spaceUid);
		}
		$threadList->execute($dataSource);
		$threads = $threadList->getList();
		$topic_type = array();
		foreach ($threads as &$v) {
			$topic_type[] = $v['topic_type'];
		}
		$topictypes = $topic_type ? app('forum.PwTopicType')->fetchTopicType($topic_type) : array();
		
		->with(array('uid' => $this->space->spaceUid), 'args');
		->with($threadList->total, 'count');
		->with($threadList->page, 'page');
		->with($threadList->perpage, 'perpage');
		->with($threadList->getList(), 'threads');
		->with($topictypes, 'topictypes');
		->with('thread', 'src');
		
		// seo设置
		Wind::import('SRV:seo.bo.PwSeoBo');
		$seoBo = PwSeoBo::getInstance();
		$lang = Wind::getComponent('i18n');

		$des = $lang->getMessage('SEO:space.thread.run.description', array($this->space->spaceUser['username']));

		if ($page <= 1) {
			$seoBo->setCustomSeo($lang->getMessage('SEO:space.thread.run.title', array($this->space->spaceUser['username'], $this->space->space['space_name'])), '', $des);

		} else {
			$seoBo->setCustomSeo($lang->getMessage('SEO:space.thread.run.page.title', array($this->space->spaceUser['username'], $page, $this->space->space['space_name'])), '', $des);
		}
		Core::setV('seo', $seoBo);
	}
	
	/**
	 * 我的回复
	 *
	 */
	public function postAction(Request $request) {
		list($page, $perpage) = $request->get(array('page', 'perpage'));
		$page = $page ? $page : 1;
		$perpage = 20;
		list($start, $limit) = Tool::page2limit($page, $perpage);
		$count = $this->_getCountPost($this->space->spaceUid,$this->loginUser->uid);
		if ($count) {
			$tmpPosts = $this->_getPost($this->space->spaceUid,$this->loginUser->uid,$limit,$start);
			$posts = $tids = array();
			foreach ($tmpPosts as $v) {
				$tids[] = $v['tid'];
			}
			$threads = $this->_getThreadDs()->fetchThread($tids);
			foreach ($tmpPosts as $v) {
				$v['threadSubject'] = Tool::substrs($threads[$v['tid']]['subject'], 30);
				$v['content'] = Tool::substrs($v['content'], 30);
				$v['created_time'] = PW::time2str($v['created_time'],'auto');
				$posts[] = $v;
			}
		}
		$args = array('uid'=>$this->space->spaceUid);
		->with($args, 'args');
		->with($count, 'count');
		->with($page, 'page');
		->with($perpage, 'perpage');
		->with($posts, 'posts');
		->with('thread', 'src');
		
		// seo设置
		Wind::import('SRV:seo.bo.PwSeoBo');
		$seoBo = PwSeoBo::getInstance();
		$lang = Wind::getComponent('i18n');
		$des = $lang->getMessage('SEO:space.thread.post.description', array($this->space->spaceUser['username']));

		if ($page <= 1) {
			$seoBo->setCustomSeo($lang->getMessage('SEO:space.thread.post.title', array($this->space->spaceUser['username'], $this->space->space['space_name'])), '', $des);
		} else {
			$seoBo->setCustomSeo($lang->getMessage('SEO:space.thread.post.page.title', array($this->space->spaceUser['username'], $page, $this->space->space['space_name'])), '', $des);
		}
		Core::setV('seo', $seoBo);
	}
	
	private function _getCountPost($spaceUid,$loginUid) {
		return ($spaceUid == $loginUid) ? $this->_getThreadExpandDs()->countDisabledPostByUid($spaceUid) : $this->_getThreadDs()->countPostByUid($spaceUid);
	}
	
	private function _getPost($spaceUid,$loginUid,$limit,$start) {
		return ($spaceUid == $loginUid) ? $this->_getThreadExpandDs()->getDisabledPostByUid($spaceUid,$limit,$start) : $this->_getThreadDs()->getPostByUid($spaceUid,$limit,$start);
	}
	
	/**
	 * Enter description here ...
	 *
	 * @return PwThreadExpand
	 */
	protected function _getThreadExpandDs() {
		return app('forum.PwThreadExpand');
	}
	
	/**
	 * Enter description here ...
	 *
	 * @return PwThread
	 */
	protected function _getThreadDs() {
		return app('forum.PwThread');
	}
}
?>