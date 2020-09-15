<?php
Wind::import('APPS:manage.controller.BaseManageController');

/**
 * 前台管理面板 - 回收站
 *
 * @author Qiong Wu <papa0924@gmail.com> 2011-10-21
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.windframework.com
 * @version $Id: RecycleController.php 28816 2013-05-24 09:45:25Z jieyin $
 * @package src.applications.manage.controller
 */
class RecycleController extends BaseManageController {
	
	/* (non-PHPdoc)
	 * @see BaseManageController::beforeAction()
	 */
	public function beforeAction($handlerAdapter) {
		parent::beforeAction($handlerAdapter);
		$result = $this->loginUser->getPermission('panel_recycle_manage', false, array());
		if (!$result['recycle']) {
			return $this->showError('BBS:recycle.right.error');
		}
	}
	
	/**
	 * 菜单管理主入口
	 * 
	 * @return void
	 */
	public function run() {
		$page = intval($request->get('page'));
		list($keyword, $fid, $author, $createdTimeStart, $createdTimeEnd, $operator, $operateTimeStart, $operateTimeEnd) = $request->get(array('keyword', 'fid', 'author', 'created_time_start', 'created_time_end', 'operator', 'operate_time_start', 'operate_time_end'));
		
		$page < 1 && $page = 1;
		$perpage = 20;
		list($start, $limit) = Tool::page2limit($page, $perpage);

		Wind::import('SRV:recycle.vo.PwRecycleThreadSo');
		$so = new PwRecycleThreadSo();
		$so->orderbyCreatedTime(0);
		$url = array();
		
		if ($keyword) {
			$so->setKeywordOfTitle($keyword);
			$url['keyword'] = $keyword;
		}
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
		if ($operator) {
			$so->setOperator($operator);
			$url['operator'] = $operator;
		}
		if ($operateTimeStart) {
			$so->setOperatorTimeStart(Tool::str2time($operateTimeStart));
			$url['operate_time_start'] = $operateTimeStart;
		}
		if ($operateTimeEnd) {
			$so->setOperatorTimeEnd(Tool::str2time($operateTimeEnd));
			$url['operate_time_end'] = $operateTimeEnd;
		}

		$service = app('recycle.PwTopicRecycle');
		$count = $service->countSearchRecord($so);
		$threaddb = $service->searchRecord($so, $limit, $start);

		->with($threaddb, 'threaddb');
		->with(app('forum.srv.PwForumService')->getForumOption($fid), 'option_html');
		->with(app('forum.PwForum')->getForumList(), 'forumname');

		->with($page, 'page');
		->with($perpage, 'perpage');
		->with($count, 'count');
		->with($url, 'url');
		
		// seo设置
		Wind::import('SRV:seo.bo.PwSeoBo');
		$seoBo = PwSeoBo::getInstance();
		$lang = Wind::getComponent('i18n');
		$seoBo->setCustomSeo($lang->getMessage('SEO:manage.recycle.run.title'), '', '');
		Core::setV('seo', $seoBo);
	}

	/**
	 * 删除主题
	 */
	public function doDeleteTopicAction(Request $request) {
		
		$tids = $request->get('tids', 'post');
		if (!$tids) {
			return $this->showError('operate.fail');
		}
		
		Wind::import('SRV:forum.srv.operation.PwDeleteTopic');
		Wind::import('SRV:forum.srv.dataSource.PwFetchTopicByTid');
		$srv = new PwDeleteTopic(new PwFetchTopicByTid($tids), $this->loginUser);
		$srv->execute();

		return $this->showMessage('删除成功了');
	}

	/**
	 * 还原主题
	 */
	public function doRevertTopicAction(Request $request) {
		
		$tids = $request->get('tids', 'post');
		if (!$tids) {
			return $this->showError('operate.fail');
		}

		Wind::import('SRV:forum.srv.operation.PwRevertTopic');
		$srv = new PwRevertTopic($tids, $this->loginUser);
		$srv->execute();

		return $this->showMessage('还原成功了');
	}
	
	/**
	 * 回收站-回复
	 */
	public function replyAction(Request $request) {
		$threaddb = $params = array();
		list($keyword, $fid, $author, $createdTimeStart, $createdTimeEnd, $operator, $operateTimeStart, $operateTimeEnd) = $request->get(array('keyword', 'fid', 'author', 'created_time_start', 'created_time_end', 'operator', 'operate_time_start', 'operate_time_end'));
		$page = intval($request->get('page'));
		$page < 1 && $page = 1;
		$perpage = 20;
		list($start, $limit) = Tool::page2limit($page, $perpage);
		
		Wind::import('SRV:recycle.vo.PwRecycleReplySo');
		$so = new PwRecycleReplySo();
		$so->orderbyCreatedTime(0);
		$url = array();
		
		if ($keyword) {
			$so->setKeywordOfTitle($keyword);
			$url['keyword'] = $keyword;
		}
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
		if ($operator) {
			$so->setOperator($operator);
			$url['operator'] = $operator;
		}
		if ($operateTimeStart) {
			$so->setOperatorTimeStart(Tool::str2time($operateTimeStart));
			$url['operate_time_start'] = $operateTimeStart;
		}
		if ($operateTimeEnd) {
			$so->setOperatorTimeEnd(Tool::str2time($operateTimeEnd));
			$url['operate_time_end'] = $operateTimeEnd;
		}

		$service = app('recycle.PwReplyRecycle');
		$count = $service->countSearchRecord($so);
		$threaddb = $service->searchRecord($so);

		->with($threaddb, 'threaddb');
		->with(app('forum.srv.PwForumService')->getForumOption($fid), 'option_html');
		->with(app('forum.PwForum')->getForumList(), 'forumname');
		
		->with($page, 'page');
		->with($perpage, 'perpage');
		->with($count, 'count');
		->with($url, 'url');
		
		// seo设置
		Wind::import('SRV:seo.bo.PwSeoBo');
		$seoBo = PwSeoBo::getInstance();
		$lang = Wind::getComponent('i18n');
		$seoBo->setCustomSeo($lang->getMessage('SEO:manage.recycle.reply.title'), '', '');
		Core::setV('seo', $seoBo);
	}

	/**
	 * 删除回复
	 */
	public function doDeleteReplyAction(Request $request) {
	
		$pids = $request->get('pids', 'post');
		if (!$pids) {
			return $this->showError('operate.fail');
		}
	
		Wind::import('SRV:forum.srv.operation.PwDeleteReply');
		Wind::import('SRV:forum.srv.dataSource.PwFetchReplyByPid');
		$srv = new PwDeleteReply(new PwFetchReplyByPid($pids), $this->loginUser);
		$srv->execute();
	
		return $this->showMessage('删除成功了');
	}
	
	/**
	 * 还原回复
	 */
	public function doRevertReplyAction(Request $request) {
	
		$pids = $request->get('pids', 'post');
		if (!$pids) {
			return $this->showError('operate.fail');
		}

		Wind::import('SRV:forum.srv.operation.PwRevertReply');
		$srv = new PwRevertReply($pids, $this->loginUser);
		$srv->execute();
	
		return $this->showMessage('还原成功了');
	}

	protected function _getFroumService() {
		return app('forum.srv.PwForumService');
	}
}
?>