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
 * 版块会员
 *
 * @author Jianmin Chen <sky_hold@163.com>
 * @license http://www.phpwind.com
 * @version $Id: UserController.php 23994 2013-01-18 03:51:46Z long.shi $
 * @package forum
 */

class UserController extends Controller{

	public function run() {
		$fid = $request->get('fid');
		$type = intval($request->get('type', 'get')); // 主题分类ID
		$page = intval($request->get('page', 'get'));
		$page < 1 && $page = 1;
		$perpage = Core::C('bbs', 'thread.perpage');
		$pwforum = new PwForumBo($fid, true);
		
		if (!$pwforum->isForum(true)) {
			return $this->showError('BBS:forum.exists.not');
		}
		if (($result = $pwforum->allowVisit($this->loginUser)) !== true) {
			return $this->showError($result->getError());
		}
		
		$totalJoin = app('forum.PwForumUser')->countUserByFid($fid);
		$joinUser = app('forum.PwForumUser')->getUserByFid($fid, 15);
		$activeUser = app('forum.srv.PwForumUserService')->getActiveUser($fid, 7, 50);
		$uids = array_merge(array_keys($joinUser), array_keys($activeUser));
		$users = app('user.PwUser')->fetchUserByUid($uids);
		
		$guide = $pwforum->headguide();
		$guide .= $this->buildBread('会员', 'bbs/user/run?fid=' . $fid);
		->with($fid, 'fid');
		->with($pwforum, 'pwforum');
		->with($guide, 'headguide');
		
		->with($totalJoin, 'totalJoin');
		->with($joinUser, 'joinUser');
		->with($activeUser, 'activeUser');
		->with($users, 'users');
		
		//版块风格
		//版块风格
		if ($pwforum->foruminfo['style']) {
			$this->setTheme('forum', $pwforum->foruminfo['style']);
			//$this->addCompileDir($pwforum->foruminfo['style']);
		}
		
		// seo设置
		Wind::import('SRV:seo.bo.PwSeoBo');
		$seoBo = PwSeoBo::getInstance();
		$lang = Wind::getComponent('i18n');
		$seoBo->setCustomSeo(
			$lang->getMessage('SEO:bbs.user.run.title', array($pwforum->foruminfo['name'])), '', 
			$lang->getMessage('SEO:bbs.user.run.description', array($pwforum->foruminfo['name'])));
		Core::setV('seo', $seoBo);
	}
}