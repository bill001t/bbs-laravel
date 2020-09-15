<?php
defined('WEKIT_VERSION') || exit('Forbidden');

Wind::import('SRV:attention.srv.PwFreshDisplay');
Wind::import('SRV:attention.srv.dataSource.PwFetchAttentionFresh');

/**
 * Enter description here ...
 *
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.windframework.com
 * @version $Id: FreshController.php 28843 2013-05-28 01:57:37Z jieyin $
 * @package wind
 */
class FreshController extends Controller{
	
	/* (non-PHPdoc)
	 * @see PwBaseController::beforeAction()
	 */
	public function beforeAction($handlerAdapter) {
		parent::beforeAction($handlerAdapter);
		if (!$this->loginUser->isExists()) {
			return redirect('u/login/run', array('backurl' => 'my/fresh/run'));
		}
    }

	/* (non-PHPdoc)
	 * @see WindController::run()
	 */
	public function run() {
		
		$page = intval($request->get('page', 'get'));
		$page < 1 && $page = 1;
		$perpage = 20;
		list($start, $limit) = Tool::page2limit($page, $perpage);
		$gid = $request->get('gid');
		$url = array();
		if ($gid) {
			$url['gid'] = $gid;
			$current = $gid;
			$user = app('attention.PwAttentionType')->getUserByType($this->loginUser->uid, $gid, 2000);
			$uids = array_keys($user);
			$count = $this->_getService()->countAttentionFreshByUid($this->loginUser->uid, $uids);
			Wind::import('SRV:attention.srv.dataSource.PwFetchAttentionFreshByUid');
			$dataSource = new PwFetchAttentionFreshByUid($this->loginUser->uid, $uids, $limit, $start);
		} else {
			$current = 'all';
			$count = $this->_getService()->countAttentionFresh($this->loginUser->uid);
			if ($count > 200) {
				$count > 250 && app('attention.PwFresh')->deleteAttentionFresh($this->loginUser->uid, $count - 200);
				$count = 200;
			}
			Wind::import('SRV:attention.srv.dataSource.PwFetchAttentionFresh');
			$dataSource = new PwFetchAttentionFresh($this->loginUser->uid, $limit, $start);
		}
		$freshDisplay = new PwFreshDisplay($dataSource);
		$fresh = $freshDisplay->gather();
		$type = app('attention.srv.PwAttentionService')->getAllType($this->loginUser->uid);
		
		$unpost = '';
		!$this->loginUser->info['lastpost'] && $this->loginUser->info['lastpost'] = $this->loginUser->info['regdate'];
		$tmp = Tool::getTime() - $this->loginUser->info['lastpost'];
		if ($tmp > 31536000) {
			$unpost = floor($tmp / 31536000) . '年多';
		} elseif ($tmp > 2592000) {
			$unpost = floor($tmp / 2592000) . '个多月';
		} elseif ($tmp > 172800) {
			$unpost = floor($tmp / 86400) . '天';
		}
		$type = app('attention.srv.PwAttentionService')->getAllType($this->loginUser->uid);

		$allowUpload = $this->loginUser->getPermission('allow_upload');
		if ($imgextsize =  Tool::subArray(Core::C('attachment', 'extsize'), array('jpg', 'jpeg', 'png', 'gif', 'bmp'))) {
			$maxSize = max($imgextsize) . ' KB';
			$filetypes = '*.' . implode(';*.', array_keys($imgextsize));
			$attachnum = intval(Core::C('attachment', 'attachnum'));
			if ($perday = $this->loginUser->getPermission('uploads_perday')) {
				$todayupload = $this->loginUser->info['lastpost'] < Tool::getTdtime() ? 0 : $this->loginUser->info['todayupload'];
				$attachnum = max(min($attachnum, $perday - $todayupload), 0);
				$attachnum == 0 && $allowUpload = 0;
			}
		} else {
			$allowUpload = $attachnum = $maxSize = 0;
			$filetypes = '';
		}

		->with($allowUpload, 'allowUpload');
		->with($attachnum, 'attachnum');
		->with($maxSize, 'maxSize');
		->with($filetypes, 'filetypes');
		
		->with($current, 'currents');
		->with($type, 'type');
		->with($unpost, 'unpost');
		->with($fresh, 'freshdb');
		->with($this->loginUser->getPermission('fresh_delete'), 'freshDelete');

		->with($page, 'page');
		->with($perpage, 'perpage');
		->with($count, 'count');
		->with($url, 'url');
		
		// seo设置
		Wind::import('SRV:seo.bo.PwSeoBo');
		$seoBo = PwSeoBo::getInstance();
		$lang = Wind::getComponent('i18n');
		$seoBo->setCustomSeo($lang->getMessage('SEO:bbs.fresh.run.title'), '', '');
		Core::setV('seo', $seoBo);
	}

	public function replyAction(Request $request) {

		$id = $request->get('id');
		
		Wind::import('SRV:attention.srv.PwFreshReplyList');
		$reply = new PwFreshReplyList($id);
		$fresh = $reply->getData();
		$replies = $reply->getReplies(7);
		$replies = app('forum.srv.PwThreadService')->displayReplylist($replies);
		
		$count = count($replies);
		if ($count > 6) {
			$replies = array_slice($replies, 0, 6, true);
		}
		->with($count, 'count');
		$this->setOutPut($replies, 'replies');
		$this->setOutPut($fresh, 'fresh');
	}

	public function doreplyAction(Request $request) {

		$id = $request->get('id');
		$content = $request->get('content', 'post');
		$transmit = $request->get('transmit', 'post');

		Wind::import('SRV:attention.srv.PwFreshReplyPost');
		$reply = new PwFreshReplyPost($id, $this->loginUser);

		if (($result = $reply->check()) !== true) {
			return $this->showError($result->getError());
		}
		$reply->setContent($content);
		$reply->setIsTransmit($transmit);

		if (($result = $reply->execute()) instanceof ErrorBag) {
			return $this->showError($result->getError());
		}
		if (!$reply->getIscheck()) {
			return $this->showError('BBS:post.reply.ischeck');
		}
		$content = app('forum.srv.PwThreadService')->displayContent($content, $reply->getIsuseubb(), $reply->getRemindUser());
		/*
		$content = Security::escapeHTML($content);
		if ($reply->getIsuseubb()) {
			Wind::import('LIB:ubb.PwSimpleUbbCode');
			Wind::import('LIB:ubb.config.PwUbbCodeConvertThread');
			$content = PwSimpleUbbCode::convert($content, 140, new PwUbbCodeConvertThread());
		}*/
		$fresh = array();
		if ($transmit && ($newId = $reply->getNewFreshSrcId())) {
			Wind::import('SRV:attention.srv.dataSource.PwFetchFreshByTypeAndSrcId');
			$data = $reply->getData();
			$freshDisplay = new PwFreshDisplay(new PwFetchFreshByTypeAndSrcId($data['type'] == 3 ? 3 : 2, array($newId)));
			$fresh = $freshDisplay->gather();
			$fresh = current($fresh);
		}

		$this->setOutPut(Tool::getTime(), 'timestamp');
		$this->setOutPut($content, 'content');
		$this->setOutPut($this->loginUser->uid, 'uid');
		$this->setOutPut($this->loginUser->username, 'username');
		$this->setOutPut($fresh, 'fresh');
	}

	public function readAction(Request $request) {
		
		Wind::import('SRV:forum.bo.PwThreadBo');
		$id = $request->get('id');
		$fresh = $this->_getService()->getFresh($id);
		if ($fresh['type'] == 1) {
			$thread = new PwThreadBo($fresh['src_id']);
			$array = $thread->info;
			$array['pid'] = 0;
		} else {
			$array = $this->_getThread()->getPost($fresh['src_id']);
			$thread = new PwThreadBo($array['tid']);
		}
		Wind::import('LIB:ubb.PwUbbCode');
		Wind::import('LIB:ubb.config.PwUbbCodeConvertThread');
		$array['content'] = Security::escapeHTML($array['content']);
		$array['content'] = str_replace("\n", '<br />', $array['content']);
		$array['useubb'] && $array['content'] = PwUbbCode::convert($array['content'], new PwUbbCodeConvertThread($thread, $array, $this->loginUser));

		echo $array['content'];
		return view('');
		//$this->setOutPut($array['content'], 'data');
		//return $this->showMessage('success');
	}

	public function postAction(Request $request) {

		$fid = $request->get('fid');
		$_getHtml = $request->get('_getHtml', 'get');
		list($content, $topictype, $subtopictype) = $request->get(array('content', 'topictype', 'sub_topictype'), 'post');

		Wind::import('SRV:forum.srv.post.PwTopicPost');
		Wind::import('SRV:forum.srv.PwPost');
		$postAction = new PwTopicPost($fid);
		$pwpost = new PwPost($postAction);
		$this->runHook('c_fresh_post', $pwpost);
		if (($result = $pwpost->check()) !== true) {
			return $this->showError($result->getError());
		}
		$postDm = $pwpost->getDm();
		$postDm->setTitle(Tool::substrs(Tool::stripWindCode($content), 30))
			->setContent($content);

		$topictype_id = $subtopictype ? $subtopictype : $topictype;
		$topictype_id && $postDm->setTopictype($topictype_id);

		if (($result = $pwpost->execute($postDm)) !== true) {
			$data = $result->getData();
			$data && $this->addMessage($data, 'data');
			return $this->showError($result->getError());
		}
		if (!$postDm->getField('ischeck')) {
			return $this->showMessage('BBS:post.topic.ischeck');
		} elseif ($_getHtml == 1) {
			Wind::import('SRV:attention.srv.dataSource.PwFetchFreshByTypeAndSrcId');
			$freshDisplay = new PwFreshDisplay(new PwFetchFreshByTypeAndSrcId(1, array($pwpost->getNewId())));
			$fresh = $freshDisplay->gather();
			$fresh = current($fresh);
			->with($fresh, 'fresh');
		} else {
			return $this->showMessage('success');
		}
	}

	public function deleteAction(Request $request) {
		
		$id = $request->get('id', 'post');
		if (!$id) {
			return $this->showError('operate.select');
		}
		if (!$this->loginUser->getPermission('fresh_delete')) {
			return $this->showError('permission.fresh.delete.deny');
		}
		Wind::import('SRV:attention.srv.operation.PwDeleteFresh');
		Wind::import('SRV:attention.srv.dataSource.PwGetFreshById');
		
		$srv = new PwDeleteFresh(new PwGetFreshById($id), $this->loginUser);
		$srv->setIsDeductCredit(true)
			->execute();

		return $this->showMessage('success');
	}

	protected function _getService() {
		return app('attention.PwFresh');
	}

	protected function _getThread() {
		return app('forum.PwThread');
	}
}