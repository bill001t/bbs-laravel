<?php
Wind::import('ADMIN:library.AdminBaseController');

/**
 * 条件扩展-帖子
 *
 * @author xiaoxia.xu <x_824@sina.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.windframework.com
 * @version $Id: TaskConditionBbsController.php 20430 2012-10-29 10:46:33Z xiaoxia.xuxx $
 * @package src.modules.task.admin
 */
class TaskConditionBbsController extends AdminBaseController {

	/* (non-PHPdoc)
	 * @see AdminBaseController::beforeAction()
	 */
	public function beforeAction($handlerAdapter) {
		parent::beforeAction($handlerAdapter);
		$var = unserialize($request->get('var'));
		if (is_array($var)) {
			->with($var, 'condition');
		}
	}
	
	/**
	 * 构建版块树
	 *
	 * @param int $parentid
	 * @param array $map
	 * @param int $level
	 * @return array
	 */
	private function _buildForumTree($parentid, $map, $level = '') {
		if (!isset($map[$parentid])) return array();
		$array = array();
		foreach ($map[$parentid] as $key => $value) {
			$value['level'] = $level;
			$value['name'] = strip_tags($value['name']);
			$array[] = $value;
			$array = array_merge($array, $this->_buildForumTree($value['fid'], $map, $level.'&nbsp;&nbsp;'));
		}
		return $array;
	}
	
	/**
	 * 获得版块列表
	 *
	 */
	private function _getForumList() {
		/* @var $forumSrv PwForumService */
		$forumSrv = app('forum.srv.PwForumService');
		$map = $forumSrv->getForumMap();
		$catedb = $map[0];
		foreach ($catedb as $_k => $_v) {
			$catedb[$_k]['name'] = strip_tags($_v['name']);
		}
		$forumList = array();
		foreach ($catedb as $value) {
			$forumList[$value['fid']] = $this->_buildForumTree($value['fid'], $map);
		}
		->with($catedb, 'catedb');
		->with($forumList, 'forumList');
	}

	/* (non-PHPdoc)
	 * @see WindController::run()
	 */
	public function run() {
		$this->_getForumList();
		return view('condition.bbs_post');
	}

	/**
	 * 回帖
	 */
	public function replyAction(Request $request) {
		return view('condition.bbs_reply');
	}

	/**
	 * 喜欢帖子
	 */
	public function likeAction(Request $request) {
		$this->_getForumList();
		return view('condition.bbs_like');
	}
}