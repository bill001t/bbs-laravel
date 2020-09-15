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
 * 出售帖 / 帖子购买
 *
 * @author Jianmin Chen <sky_hold@163.com>
 * @license http://www.phpwind.com
 * @version $Id: BuythreadController.php 28868 2013-05-28 04:06:20Z jieyin $
 * @package forum
 */

class BuythreadController extends Controller{

	public function run() {

		
	}

	public function recordAction(Request $request) {
		list($tid, $pid, $page) = $request->get(array('tid', 'pid', 'page'));
		$perpage = 10;
		$page < 1 && $page = 1;
		list($offset, $limit) = Tool::page2limit($page, $perpage);
		$count = app('forum.PwThreadBuy')->countByTidAndPid($tid, $pid);
		if (!$count) {
			return $this->showError('BBS:thread.buy.error.norecord');
		}
		Wind::import('SRV:credit.bo.PwCreditBo');
		$record = app('forum.PwThreadBuy')->getByTidAndPid($tid, $pid, $limit, $offset);
		$users = app('user.PwUser')->fetchUserByUid(array_keys($record));
			
		$data = array();
		$cType = PwCreditBo::getInstance()->cType;
		foreach ($record as $key => $value) {
			$data[] = array(
				'uid' => $value['created_userid'],
				'username' => $users[$value['created_userid']]['username'],
				'cost' => $value['cost'],
				'ctype' => $cType[$value['ctype']],
				'created_time' => Tool::time2str($value['created_time'])
			);
		}
		$totalpage = ceil($count / $perpage);
		$nextpage = $page+1;
		$nextpage = $nextpage > $totalpage ? $totalpage : $nextpage;
		
		->with(array('data' => $data, 'totalpage' => $totalpage, 'page' => $nextpage), 'data');
		return $this->showMessage('success');
	}

	public function buyAction(Request $request) {
		
		list($tid, $pid) = $request->get(array('tid', 'pid'));
		$submit = (int)$request->get('submit', 'post');
		if (!$this->loginUser->isExists()) {
			return $this->showError('login.not');
		}
		if (!$tid) {
			return $this->showError('data.error');
		}
		if ($pid) {
			$result = app('forum.PwThread')->getPost($pid);
		} else {
			$pid = 0;
			$result = app('forum.PwThread')->getThread($tid, PwThread::FETCH_ALL);
		}
		if (empty($result) || $result['tid'] != $tid) {
			return $this->showError('data.error');
		}
		$start = strpos($result['content'], '[sell=');
		if ($start === false) {
			return $this->showError('BBS:thread.buy.error.sell.not');
		}
		$start += 6;
		$end = strpos($result['content'], ']', $start);
		$cost = substr($result['content'], $start, $end - $start);

		list($creditvalue, $credittype) = explode(',', $cost);
		Wind::import('SRV:credit.bo.PwCreditBo');
		$creditBo = PwCreditBo::getInstance();
		isset($creditBo->cType[$credittype]) || $credittype = key($creditBo->cType);
		$creditType = $creditBo->cType[$credittype];
		if ($result['created_userid'] == $this->loginUser->uid) {
			return $this->showError('BBS:thread.buy.error.self');
		}
		if (app('forum.PwThreadBuy')->get($tid, $pid, $this->loginUser->uid)) {
			return $this->showError('BBS:thread.buy.error.already');
		}
		
		if (($myCredit = $this->loginUser->getCredit($credittype)) < $creditvalue) {
			return $this->showError(array('BBS:thread.buy.error.credit.notenough',array('{myCredit}' => $myCredit.$creditType, '{count}' => $creditvalue.$creditType)));
		}
		
		!$submit && return $this->showMessage(array('BBS:thread.buy.message.buy', array('{count}' => $myCredit.$creditType, '{buyCount}' => -$creditvalue.$creditType)));
		Wind::import('SRV:forum.dm.PwThreadBuyDm');
		$dm = new PwThreadBuyDm();
		$dm->setTid($tid)
			->setPid($pid)
			->setCreatedUserid($this->loginUser->uid)
			->setCreatedTime(Tool::getTime())
			->setCtype($credittype)
			->setCost($creditvalue);
		app('forum.PwThreadBuy')->add($dm);

		$creditBo->addLog('buythread', array($credittype => -$creditvalue), $this->loginUser, array(
			'title' => $result['subject'] ? $result['subject'] : Tool::substrs($result['content'], 20)
		));
		$creditBo->set($this->loginUser->uid, $credittype, -$creditvalue, true);
		
		$user = new PwUserBo($result['created_userid']);
		if (($max = $user->getPermission('sell_credit_range.maxincome')) && app('forum.PwThreadBuy')->sumCost($tid, $pid) > $max) {

		} else {
			$creditBo->addLog('sellthread', array($credittype => $creditvalue), $user, array(
				'title' => $result['subject'] ? $result['subject'] : Tool::substrs($result['content'], 20)
			));
			$creditBo->set($user->uid, $credittype, $creditvalue, true);
		}
		$creditBo->execute();
		
		if ($pid) {
			Wind::import('SRV:forum.dm.PwReplyDm');
			$dm = new PwReplyDm($pid);
			$dm->addSellCount(1);
			app('forum.PwThread')->updatePost($dm);
		} else {
			Wind::import('SRV:forum.dm.PwTopicDm');
			$dm = new PwTopicDm($tid);
			$dm->addSellCount(1);
			app('forum.PwThread')->updateThread($dm, PwThread::FETCH_CONTENT);
		}

		return $this->showMessage('success', 'bbs/read/run/?tid=' . $tid . '&fid=' . $result['fid'], true);
	}
}