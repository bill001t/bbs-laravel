<?php

namespace App\Http\Controllers\bbs\controller;

use App\Core\Tool;
use App\Core\MessageTool;
use App\Http\Controllers\Controller;
use App\Services\forum\bm\PwForumService;
use App\Services\forum\bm\PwThreadList;
use App\Services\forum\bm\threadList\PwNewThread;
use App\Services\forum\bs\PwThreadIndex;
use App\Services\seo\bo\PwSeoBo;
use Core;
use Illuminate\Http\Request;

/**
 * 帖子阅读页
 *
 * @author Jianmin Chen <sky_hold@163.com>
 * @license http://www.phpwind.com
 * @version $Id: ReadController.php 24888 2013-02-25 08:12:54Z jieyin $
 * @package forum
 */
class ReadController extends Controller{

	/**
	 * 帖子阅读页
	 */
	public function run() {
		$tid = intval($request->get('tid'));
		list($page, $uid, $desc) = $request->get(array('page', 'uid', 'desc'), 'get');
		
		$threadDisplay = new PwThreadDisplay($tid, $this->loginUser);
		$this->runHook('c_read_run', $threadDisplay);
		
		if (($result = $threadDisplay->check()) !== true) {
			return $this->showError($result->getError());
		}
		$_cache = Core::cache()->fetch(array('level', 'group_right'));

		$pwforum = $threadDisplay->getForum();
		if ($pwforum->foruminfo['password']) {
			if (!$this->loginUser->isExists()) {
				return redirect('u/login/run', array('backurl' => url('bbs/cate/run', array('fid' => $$pwforum->fid))));
			} elseif (Tool::getPwdCode($pwforum->foruminfo['password']) != Tool::getCookie('fp_' . $pwforum->fid)) {
				return redirect('bbs/forum/password', array('fid' => $pwforum->fid));
			}
		}
		if ($uid) {
			Wind::import('SRV:forum.srv.threadDisplay.PwUserRead');
			$dataSource = new PwUserRead($threadDisplay->thread, $uid);
		} else {
			Wind::import('SRV:forum.srv.threadDisplay.PwCommonRead');
			$dataSource = new PwCommonRead($threadDisplay->thread);
		}
		$dataSource->setPage($page)
			->setPerpage($pwforum->forumset['readperpage'] ? $pwforum->forumset['readperpage'] : Core::C('bbs', 'read.perpage'))
			->setDesc($desc);
		
		$threadDisplay->setImgLazy(Core::C('bbs', 'read.image_lazy'));
		$threadDisplay->execute($dataSource);
		
		$operateReply = $operateThread = array();
		$isBM = $pwforum->isBM($this->loginUser->username);
		if ($threadPermission = $this->loginUser->getPermission('operate_thread', $isBM, array())) {
			$operateReply = Tool::subArray(
				$threadPermission, 
				array('toppedreply',/* 'unite', 'split',  */'remind', 'shield', 'delete', 'ban', 'inspect', 'read')
			);
			$operateThread = Tool::subArray(
				$threadPermission, 
				array(
					'digest', 'topped', 'up', 'highlight', 
					'copy', 
					'type', 'move', /*'unite', 'print' */ 'lock', 
					'down', 
					'delete', 
					'ban'
				)
			);
		}
		$threadInfo = $threadDisplay->getThreadInfo();
		->with($threadDisplay, 'threadDisplay');
		->with($tid, 'tid');
		->with($threadDisplay->fid, 'fid');
		->with($threadInfo, 'threadInfo');
		->with($threadDisplay->getList(), 'readdb');
		->with($threadDisplay->getUsers(), 'users');
		->with($pwforum, 'pwforum');
		->with(PwCreditBo::getInstance(), 'creditBo');
		->with($threadDisplay->getHeadguide(), 'headguide');
		->with(Core::C('bbs', 'read.display_member_info'), 'displayMemberInfo');
		->with(Core::C('bbs', 'read.display_info'), 'displayInfo');
		->with(Core::C('bbs', 'thread.hotthread_replies'), 'hotIcon');

		->with($threadPermission, 'threadPermission');
		->with($operateThread, 'operateThread');
		->with($operateReply, 'operateReply');
		->with((!$this->loginUser->uid && !$this->allowPost($pwforum)) ? ' J_qlogin_trigger' : '', 'postNeedLogin');
		->with((!$this->loginUser->uid && !$this->allowReply($pwforum)) ? ' J_qlogin_trigger' : '', 'replyNeedLogin');
		
		->with($_cache['level']['ltitle'], 'ltitle');
		->with($_cache['level']['lpic'], 'lpic');
		->with($_cache['level']['lneed'], 'lneed');
		->with($_cache['group_right'], 'groupRight');
		
		->with($threadDisplay->page, 'page');
		->with($threadDisplay->perpage, 'perpage');
		->with($threadDisplay->total, 'count');
		->with($threadDisplay->maxpage, 'totalpage');
		->with($threadDisplay->getUrlArgs(), 'urlargs');
		->with($threadDisplay->getUrlArgs('desc'), 'urlDescArgs');
		->with($this->loginUser->getPermission('look_thread_log', $isBM, array()), 'canLook');
		->with($this->_getFpage($threadDisplay->fid), 'fpage');
		
		//版块风格
		if ($pwforum->foruminfo['style']) {
			$this->setTheme('forum', $pwforum->foruminfo['style']);
			//$this->addCompileDir($pwforum->foruminfo['style']);
		}
		
		// seo设置
		Wind::import('SRV:seo.bo.PwSeoBo');
		$seoBo = PwSeoBo::getInstance();
		$lang = Wind::getComponent('i18n');
		$threadDisplay->page <=1 && $seoBo->setDefaultSeo($lang->getMessage('SEO:bbs.read.run.title'), '', $lang->getMessage('SEO:bbs.read.run.description'));
		$seoBo->init('bbs', 'read');
		$seoBo->set(
			array(
				'{forumname}' => $threadDisplay->forum->foruminfo['name'], 
				'{title}' => $threadDisplay->thread->info['subject'], 
				'{description}' => Tool::substrs($threadDisplay->thread->info['content'], 100, 0, false),
				'{classfication}' => $threadDisplay->thread->info['topic_type'], 
				'{tags}' => $threadInfo['tags'],
				'{page}' => $threadDisplay->page
			)
		);
		Core::setV('seo', $seoBo);
		//是否显示回复
		$showReply = true;
		//锁定时间
		if ($pwforum->forumset['locktime'] && ($threadInfo['created_time'] + $pwforum->forumset['locktime'] * 86400) < Tool::getTime()) {
			$showReply = false;
		} elseif (Tool::getstatus($threadInfo['tpcstatus'], PwThread::STATUS_LOCKED) && !$this->loginUser->getPermission('reply_locked_threads')) {
			$showReply = false;
		}
		->with($showReply, 'showReply');
		$this->runReadDesign($threadDisplay->fid);
		$this->updateReadOnline($threadDisplay->fid, $tid);
	}

	/**
	 * 帖子阅读页-楼层跳转
	 */
	public function jumpAction(Request $request) {
		$tid = $request->get('tid');
		$pid = $request->get('pid');
		if (!$tid) {
			$post = app('forum.PwThread')->getPost($pid);
			$tid = $post['tid'];
		}
		Wind::import('SRV:forum.bo.PwForumBo');
		$thread = app('forum.PwThread')->getThread($tid);
		$pwforum = new PwForumBo($thread['fid']);
		$perpage = $pwforum->forumset['readperpage'] ? $pwforum->forumset['readperpage'] : Core::C('bbs', 'read.perpage');
		$count = app('forum.PwThread')->countPostByTidUnderPid($tid, $pid) + 1;
		$page = ceil(($count + 1) / $perpage);
		
		return redirect('bbs/read/run/', array('tid' => $tid, 'fid' => $thread['fid'], 'page' => $page), $pid));
	}

	/**
	 * 帖子阅读页-下一页
	 */
	public function nextAction(Request $request) {
		$tid = $request->get('tid');
		$thread = app('forum.PwThread')->getThread($tid);
		if (!$thread) {
			return $this->showError('thread.not');
		}
		$nextThread = app('forum.PwThreadExpand')->getThreadByFidUnderTime($thread['fid'], $thread['lastpost_time'], 1);
		if ($nextThread) {
			$nextTid = key($nextThread);
			return redirect('bbs/read/run/', array('tid' => $nextTid, 'fid' => $thread['fid'])));
		} else {
			return redirect('bbs/thread/run/', array('fid' => $thread['fid'])));
		}
	}

	/**
	 * 帖子阅读页-上一页
	 */
	public function preAction(Request $request) {
		$tid = $request->get('tid');
		$thread = app('forum.PwThread')->getThread($tid);
		if (!$thread) {
			return $this->showError('thread.not');
		}
		$preThread = app('forum.PwThreadExpand')->getThreadByFidOverTime($thread['fid'], $thread['lastpost_time'], 1);
		if ($preThread) {
			$preTid = key($preThread);
			return redirect('bbs/read/run/', array('tid' => $preTid, 'fid' => $thread['fid'])));
		} else {
			return redirect('bbs/thread/run/', array('fid' => $thread['fid'])));
		}
	}

	/**
	 * 查看帖子操作日志
	 */
	public function logAction(Request $request) {
		list($tid, $fid) = $request->get(array('tid', 'fid'));
		Wind::import('SRV:forum.bo.PwForumBo');
		$forum = new PwForumBo($fid);
		$permission = $this->loginUser->getPermission('look_thread_log', $forum->isBM($this->loginUser->username), array());
		if ($permission) {
			$list = app('log.srv.PwLogService')->getThreadLog($tid, 25, 0);
			->with($list, 'list');
			return view('read_log');
		} 
	}
	
	/**
	 * 更新阅读页在线状态
	 */
	protected function updateReadOnline($fid = 0, $tid = 0) {
		if ($this->loginUser->uid < 1) return false;
		$service = app('online.srv.PwOnlineService');
		$createdTime = $service->forumOnline($fid);
		if (!$createdTime) return false;
		$dm = app('online.dm.PwOnlineDm');
		$time = Tool::getTime();
		$dm->setUid($this->loginUser->uid)->setUsername($this->loginUser->username)->setModifytime($time)->setCreatedtime($createdTime)->setGid($this->loginUser->gid)->setFid($fid)->setTid($tid)->setRequest($this->_mca);
		app('online.PwUserOnline')->replaceInfo($dm);
	}
	
	protected function runReadDesign($fid = 0) {
		Wind::import('SRV:design.bo.PwDesignPageBo');
    	$bo = new PwDesignPageBo();
    	$pageid = $bo->getPageId('bbs/read/run', '帖子阅读页', $fid);
		$pageid && $this->forward->getWindView()->compileDir = 'DATA:compile.design.'.$pageid;
		return true;
	}

	private function allowPost(PwForumBo $forum) {
		return $forum->foruminfo['allow_post'] ? $forum->allowPost($this->loginUser) : $this->loginUser->getPermission('allow_post');
	}

	private function allowReply(PwForumBo $forum) {
		return $forum->foruminfo['allow_reply'] ? $forum->allowPost($this->loginUser) : $this->loginUser->getPermission('allow_reply');
	}

	private function _getFpage($fid) {
		$fpage = 1;
		if ($referer = Tool::getCookie('visit_referer')) {
			$tmp = explode('_', $referer);
			if ($tmp[0] == 'fid' && $tmp[1] == $fid) {
				$fpage = intval($tmp[3]);
			}
		}
		return $fpage;
	}
}
