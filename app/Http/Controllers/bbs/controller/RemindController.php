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
 * @提醒Controller
 *
 * @author jinlong.panjl <jinlong.panjl@aliyun-inc.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id$
 * @package wind
 */

class RemindController extends Controller{
	
	public function beforeAction($handlerAdapter) {
		parent::beforeAction($handlerAdapter);
		if ($this->loginUser->uid < 1) {
			return $this->showError('login.not');
		}
		if ($this->loginUser->getPermission('remind_open') < 1) {
			return $this->showError('bbs:remind.remind_open.error');
		}
	}
	
	/**
	 * @下拉获取用户数据
	 *
	 * @return void
	 */
	public function run() {
		$username = $request->get('username');
		if (!$username) {
			$remindData = $this->_getRemindDs()->getByUid($this->loginUser->uid);
			$remindData && $reminds = unserialize($remindData['touid']);
			$count = count($reminds);
			$count < 10 && $num = 10 - $count;
		}
		if ($username || $num) {
			$count = $this->loginUser->info['follows'];
			if ($count) {
				$num = $num ? $num : 2000;
				$follows = $this->_getAttentionDs()->getFollows($this->loginUser->uid, $num);
				$follows = array_keys($follows);
			}
		}
		$uids = array_unique(array_merge((array)$reminds,(array)$follows));
		Tool::echoJson(array('state' => 'success', 'data' => $this->_buildRemindUsers($uids)));exit;
	}
	
	/**
	 * @提醒获取好友弹窗
	 *
	 * @return void
	 */
	public function friendAction(Request $request) {
		$remindData = $this->_getRemindDs()->getByUid($this->loginUser->uid);
		$remindData && $uids = unserialize($remindData['touid']);
		$reminds = $this->_buildRemindUsers($uids);
		$typeArr = $this->_getAttentionService()->getAllType($this->loginUser->uid);
		$todayNum = $this->_getRemindToday();
		
		->with($todayNum, 'todayNum');
		->with($reminds, 'reminds');
		->with($typeArr, 'typeArr');
	}
	
	/** 
	 * 获取用户关注数据，ajax输出
	 *
	 */
	public function getfollowAction(Request $request) {
		list($type, $page, $perpage) = $request->get(array('type', 'page', 'perpage'));
		$page = $page ? $page : 1;
		$perpage = $perpage ? $perpage : $this->perpage;
		list($start, $limit) = Tool::page2limit($page, $perpage);
		$typeCounts = $this->_getAttentionTypeDs()->countUserType($this->loginUser->uid);
		
		if ($type) {
			$tmp = $this->_getAttentionTypeDs()->getUserByType($this->loginUser->uid, $type, $limit, $start);
			$follows = $this->_getAttentionDs()->fetchFollows($this->loginUser->uid, array_keys($tmp));
			$count = $typeCounts[$type] ? $typeCounts[$type]['count'] : 0;
		} else {
			$follows = $this->_getAttentionDs()->getFollows($this->loginUser->uid, $limit, $start);
			$count = $this->loginUser->info['follows'];
		}
		$uids = array_keys($follows);
		Tool::echoJson(array('state' => 'success', 'data' => $this->_buildRemindUsers($uids), 'page' => $page));exit;
	}
	
	/** 
	 * 组装用户
	 *
	 */
	private function _buildRemindUsers($uids) {
		$userList = $this->_getUserDs()->fetchUserByUid($uids, PwUser::FETCH_MAIN);
		$users = array();
		foreach ($uids as $v) {
			if (!isset($userList[$v]['username'])) continue;
			$users[$v] = $userList[$v]['username'];
		}	
		return $users;
	}
	
	private function _getRemindToday() {
		$maxNum = $this->loginUser->getPermission('remind_max_num');
		if ($maxNum < 1) {
			return '';
		}
		$behavior = $this->_getUserBehaviorDs()->getBehavior($this->loginUser->uid,'remind_today');
		$todayNum = $maxNum - $behavior['number'];
		return $todayNum > 0 ? $todayNum : 0;
	}
	
	/**
	 * PwAttentionService
	 * 
	 * @return PwAttentionService
	 */
	private function _getAttentionService() {
		return app('attention.srv.PwAttentionService');
	}
	
	/**
	 * @return PwAttentionType
	 */
	private function _getAttentionTypeDs() {
		return app('attention.PwAttentionType');
	}
	
	/**
	 * @return PwRemind
	 */
	private function _getRemindDs(){
		return app('remind.PwRemind');
	}
	
	/**
	 * PwUserBehavior
	 * 
	 * @return PwUserBehavior
	 */
	private function _getUserBehaviorDs() {
		return app('user.PwUserBehavior');
	}
	
	/**
	 * PwAttention
	 * 
	 * @return PwAttention
	 */
	private function _getAttentionDs(){
		return app('attention.PwAttention');
	}

	/**
	 * @return PwUser
	 */
	protected function _getUserDs(){
		return app('user.PwUser');
	}
}
