<?php
Wind::import('ADMIN:library.AdminBaseController');
Wind::import('SRV:log.so.PwLogSo');

/**
 * 前台管理日志
 *
 * @author xiaoxia.xu<xiaoxia.xuxx@aliyun-inc.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.windframework.com
 * @version $Id: ManageController.php 23742 2013-01-15 09:22:58Z jieyin $
 * @package src.applications.log.admin
 */
class ManageController extends AdminBaseController {
	protected $perpage = 10;
	
	/* (non-PHPdoc)
	 * @see WindController::run()
	 */
	public function run() {
		$page = intval($request->get('page'));
		($page < 1) && $page = 1;
		$logSo = new PwLogSo();
		$logSo->setEndTime($request->get('end_time'))
			->setStartTime($request->get('start_time'))
			->setCreatedUsername($request->get('created_user'))
			->setOperatedUsername($request->get('operated_user'))
			->setFid($request->get('fid'))
			->setIp($request->get('ip'))
			->setKeywords($request->get('keywords'))
			->setTypeid($request->get('typeid'));
		/* @var $logDs PwLog */
		$logDs = app('log.PwLog');
		$count = $logDs->coutSearch($logSo);
		/* @var $logSrv PwLogService */
		$logSrv = app('log.srv.PwLogService');
		$list = array();
		if ($count > 0) {
			($page > $count) && $page = $count;
			$totalPage = ceil($count / $this->perpage);
			list($offset, $limit) = Tool::page2limit($page, $this->perpage);
			$list = $logSrv->searchManageLogs($logSo, $limit, $offset);
		}
		->with($logSrv->getOperatTypeid(), 'typeids');
		->with($logSrv->getOperatTypeTitle(), 'typeTitles');
		->with($this->perpage, 'perpage');
		->with($list, 'list');
		->with($count, 'count');
		->with($page, 'page');
		->with($logSo->getSearchData(), 'searchData');
		$this->_getForumList();
		->with($this->isFounder($this->loginUser->username), 'canClear');
	}
	
	/**
	 * 清除三个月前操作
	 */
	public function clearAction(Request $request) {
		if (!$this->isFounder($this->loginUser->username)) return $this->showError('fail');
		$step = $request->get('step', 'post');
		if ($step != 2) return $this->showError('fail');
		list($year, $month) = explode('-', Tool::time2str(Tool::getTime(), 'Y-n'));
		if ($month > 3) {
			$month = $month - 3;
		} else {
			$month = 9 - $month;
			$year = $year - 1;
		}
		app('log.PwLog')->clearLogBeforeDatetime(Tool::str2time($year . '-' . $month . '-1'));
		return $this->showMessage('success');
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
		->with($forumSrv->getForumList(), 'allForumList');
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
}