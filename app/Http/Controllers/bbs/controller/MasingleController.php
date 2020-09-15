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
 * @author peihong <jhqblxt@gmail.com> Dec 2, 2011
 * @link
 * @copyright
 * @license
 */

class MasingleController extends Controller{
	
	public $action;

	protected $manage;
	protected $doAction;
	protected $_doCancel = array();

	protected $_hasThread = false;
	protected $_jumpurl = '';

	public function beforeAction($handlerAdapter) {
		parent::beforeAction($handlerAdapter);
		if (!$this->loginUser->isExists()) {
			return $this->showError('login.not');
		}
		$this->action = $handlerAdapter->getAction();
		$this->manage = $this->_getManage($this->action);
		if (($result = $this->manage->check()) !== true) {
			return $this->showError($result->getError());
		}
	}

	public function manageAction(Request $request) {
		if (!$this->doAction) {
			$reason = Core::C()->site->get('managereasons', '');
			->with(explode("\n", $reason), 'manageReason');
			->with($this->action, 'action');
			->with(count($this->manage->data), 'count');
			return view('masingle_threads');
		} else {
			$sendnotice = $request->get('sendnotice', 'post');
			$this->manage->execute();
			if ($sendnotice) {
				$this->_sendMessage($this->action, $this->manage->getData());
			}
			if ($this->action == 'dodelete' && $this->_hasThread) {
				$this->_jumpurl = 'bbs/thread/run?fid=' . current($this->manage->getFids());
			}
			return $this->showMessage('operate.success', $this->_jumpurl);
		}
	}

	protected function _getManage($action) {
		$pids = $request->get('pids', 'post');
		$tid = $request->get('tid', 'post');
		$pid = $request->get('pid', 'post');
		if ($pids && !is_array($pids)) {
			$pids = explode(',', $pids);
		} elseif (!$pids && $pid) {
			$pids = array($pid);
		}
		if (!$pids) {
			return $this->showError('operate.select');
		}
		in_array('0', $pids) && $this->_hasThread = true;
		$manage = new PwThreadManage(new PwFetchReplyByTidAndPids($tid, $pids), $this->loginUser);
		
		if (strpos($action, 'do') === 0) {
			$getMethod = sprintf('_get%sManage', ucfirst(substr($action, 2)));
			$this->doAction = true;
		} else {
			$getMethod = sprintf('_get%sManage', ucfirst($action));
			$this->doAction = false;
		}
		if (method_exists($this, $getMethod)) {
			$do = $this->$getMethod($manage);
			$manage->appendDo($do);
		}
		return $manage;
	}

	protected function _getDeleteManage($manage) {
		Wind::import('SRV:forum.srv.manage.PwThreadManageDoDeleteReply');
		$do = new PwThreadManageDoDeleteReply($manage);
		if (!$this->doAction) {
			->with('dodelete', 'doaction');
		} else {
			$deductCredit = $request->get('deductCredit', 'post');
			$reason = $request->get('reason', 'post');
			$do->setIsDeductCredit($deductCredit)
				->setReason($reason);
		}
		return $do;
	}

	/**
	 * 已阅操作
	 *
	 * @param obj $manage
	 * @return PwThreadManageDoInspect
	 */
	protected function _getInspectManage($manage) {
		Wind::import('SRV:forum.srv.manage.PwThreadManageDoInspect');
		$do = new PwThreadManageDoInspect($manage);
		if (!$this->doAction) {
			return $this->showError('data.error');
		} else {
			$lou = $request->get('lou');
			$do->setLou($lou);
		}
		return $do;
	}

	/**
	 * 屏蔽操作
	 *
	 * @param obj $manage
	 * @return PwThreadManageDoInspect
	 */
	protected function _getShieldManage($manage) {
		Wind::import('SRV:forum.srv.manage.PwThreadManageDoShield');
		$do = new PwThreadManageDoShield($manage);
		if (!$this->doAction) {
			->with('doshield', 'doaction');
			->with($manage->data[0]['ifshield'], 'defaultShield');
		} else {
			list($reason,$ifShield) = $request->get(array('reason','ifShield'), 'post');
			$do->setReason($reason)->setIfShield($ifShield);
			!$ifShield && $this->_doCancel[] = 'doshield';
		}
		return $do;
	}

	/**
	 * 提醒操作
	 *
	 * @param obj $manage
	 * @return PwThreadManageDoInspect
	 */
	protected function _getRemindManage($manage) {
		Wind::import('SRV:forum.srv.manage.PwThreadManageDoRemind');
		$do = new PwThreadManageDoRemind($manage);
		if (!$this->doAction) {
			->with('doremind', 'doaction');
			->with($manage->data[0]['manage_remind'], 'defaultRemind');
		} else {
			list($reason,$ifRemind) = $request->get(array('reason','ifRemind'), 'post');
			$do->setReason($reason)->setIfRemind($ifRemind);
			!$ifRemind && $this->_doCancel[] = 'doremind';
		}
		return $do;
	}

	/**
	 * 帖内置顶操作
	 *
	 * @param obj $manage
	 * @return PwThreadManageDoInspect
	 */
	protected function _getToppedReplyManage($manage) {
		Wind::import('SRV:forum.srv.manage.PwThreadManageDoToppedReply');
		$do = new PwThreadManageDoToppedReply($manage);
		if (!$this->doAction) {
			return $this->showError('data.error');
		} else {
			list($lou,$topped) = $request->get(array('lou','topped'));
			$do->setLou($lou)->setTopped($topped);
		}
		return $do;
	}
	
	/* (non-PHPdoc)
	 * @see WindController::resolvedActionMethod()
	 */
	public function resolvedActionMethod($handlerAdapter) {
		return $this->resolvedActionName('manage');
	}

	/**
	 * send messages
	 */
	protected function _sendMessage($action, $threads) {
		if (!is_array($threads) || !$threads || !$action) return false;
		$noticeService = app('message.srv.PwNoticeService');
		$reason = $request->get('reason');
		foreach ($threads as $thread) {
			$params = array();
			$params['manageUsername'] = $this->manage->user->username;
			$params['manageUserid'] = $this->manage->user->uid;
			$params['manageThreadTitle'] = $thread['subject'];
			$params['manageThreadId'] = $thread['tid'];
			$params['manageTypeString'] = $this->_getManageActionName($action);
			$reason && $params['manageReason'] = $reason;
			$noticeService->sendNotice($thread['created_userid'], 'threadmanage', $thread['tid'], $params);
		}
		return true;
	}
	
	protected function _getManageActionName($action) {
		$resource = app(MessageTool::class);
		$message = $resource->getMessage("BBS:manage.operate.name.$action");
		if (in_array($action, $this->_doCancel)) {
			$message = $resource->getMessage("BBS:manage.operate.action.cancel") . $message;
		}
		return $message;
	}	
}