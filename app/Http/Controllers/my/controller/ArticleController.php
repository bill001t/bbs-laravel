<?php
Wind::import('LIB:base.PwBaseController');

/**
 * 我的帖子回复
 *
 * @author jinlong.panjl <jinlong.panjl@aliyun-inc.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: ArticleController.php 23994 2013-01-18 03:51:46Z long.shi $
 * @package wind
 */
class ArticleController extends Controller{
	private $perpage = 20;
	
	public function beforeAction($handlerAdapter) {
		parent::beforeAction($handlerAdapter);
		if (!$this->loginUser->isExists()) {
			return redirect('u/login/run',array('backurl' => 'my/article/run'));
		}
	}
	
	/* (non-PHPdoc)
	 * @see WindController::run()
	 */
	public function run() {
		Wind::import('SRV:forum.srv.PwThreadList');
		list($page, $perpage) = $request->get(array('page', 'perpage'));
		$page = $page ? $page : 1;
		$perpage = $perpage ? $perpage : $this->perpage;
		$threadList = new PwThreadList();

		$threadList->setPage($page)->setPerpage($perpage);
		Wind::import('SRV:forum.srv.threadList.PwMyThread');
		$dataSource = new PwMyThread($this->loginUser->uid);

		$threadList->execute($dataSource);
		$threads = $threadList->getList();
		$topic_type = array();
		foreach ($threads as &$v) {

			$topic_type[] = $v['topic_type'];

		}
		$topictypes = $topic_type ? app('forum.PwTopicType')->fetchTopicType($topic_type) : array();
		
		->with($threadList->total, 'count');
		->with($threadList->page, 'page');
		->with($threadList->perpage, 'perpage');
		->with($threads, 'threads');
		->with($topictypes, 'topictypes');
		
		// seo设置

		Wind::import('SRV:seo.bo.PwSeoBo');
		$seoBo = PwSeoBo::getInstance();
		$lang = Wind::getComponent('i18n');

		$seoBo->setCustomSeo($lang->getMessage('SEO:bbs.article.run.title'), '', '');
		Core::setV('seo', $seoBo);
	}
	
	/**
	 * 回复列表
	 */
	public function replyAction(Request $request) {
		list($page, $perpage) = $request->get(array('page', 'perpage'));
		$page = $page ? $page : 1;
		$perpage = $perpage ? $perpage : $this->perpage;
		list($start, $limit) = Tool::page2limit($page, $perpage);
		$count = $this->_getThreadExpandDs()->countDisabledPostByUid($this->loginUser->uid);
		if ($count) {
			$tmpPosts = $this->_getThreadExpandDs()->getDisabledPostByUid($this->loginUser->uid,$limit,$start);
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
		->with($count, 'count');
		->with($page, 'page');
		->with($perpage, 'perpage');
		->with($posts, 'posts');
		
		// seo设置

		Wind::import('SRV:seo.bo.PwSeoBo');
		$seoBo = PwSeoBo::getInstance();
		$lang = Wind::getComponent('i18n');
		$seoBo->setCustomSeo($lang->getMessage('SEO:bbs.article.reply.title'), '', '');
		Core::setV('seo', $seoBo);
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