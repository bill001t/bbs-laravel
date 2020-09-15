<?php

namespace App\Services\forum\bm;

use App\Services\forum\bs\PwForum;
use App\Services\forum\bs\PwThreadExpand;
use App\Services\user\bm\PwUserMiscService;

/**
 * 版块服务接口(不常用的业务逻辑)
 */

class PwForumMiscService
{
	
	/**
	 * 用户被禁言的时候同步删除该用户的版主权限
	 *
	 * @param string $manage  被禁言的用户名
	 * @return boolean
	 */
	public function updateDataByUser($manage) {
		$manage = trim($manage);
		$forums = $this->_getForum()->getForumOrderByType();
		foreach ($forums as $key => $value) {
			$manager = str_replace(',' . $manage . ',', ',', $value['manager']);
			$upmanager = str_replace(',' . $manage . ',', ',', $value['uppermanager']);
			if ($manager != $value['manager'] || $upmanager = $value['uppermanager']) {
				$dm = [];
				$dm['fid'] = $key;
				$dm['manager'] = $manager;
				$dm['uppermanager'] = $upmanager;

				$this->_getForum()->updateForum($dm);
			}
		}
		return true;
	}
	
	/**
	 * 纠正版块额外的数据(上级版块、是否含有子版等统计数据)
	 *
	 * haha,remember the order!
	 */
	public function correctData() {
		$manager = $fups = $fupnames = array(0 => '');
		$hassub = $subFids = $allManager = array();
		$forums = $this->_getForum()->getForumOrderByType();

		foreach ($forums as $key => $value) {
			if ($value['parentid']) $hassub[$value['parentid']] = 1;
			if ($value['hassub']) $subFids[] = $value['fid'];
			$uppermanager = $manager[$value['parentid']];
			$fup = $fups[$value['parentid']];
			$fupname = $fupnames[$value['parentid']];

			if ($uppermanager != $value['uppermanager'] || $fup != $value['fup'] || $fupname != $value['fupname']) {
				$dm = [];
				$dm['fid'] = $key;
				$dm['uppermanager'] = $uppermanager;
				$dm['fupname'] = $fupname;
				$dm['fup'] = $fup;

				$this->_getForum()->updateForum($dm, PwForum::FETCH_MAIN);
			}

			if ($value['manager'] = trim($value['manager'], ',')) {
				$allManager = array_merge($allManager, explode(',', $value['manager']));
				$uppermanager = rtrim($uppermanager, ',') . ',' . $value['manager'] . ',';
			}

			$manager[$key] = $uppermanager;
			$fups[$key] = $key . ($fup ? (',' . $fup) : '');
			$fupnames[$key] = strip_tags($value['name']) . ($fupname ? ("\t" . $fupname) : '');
		}

		$hassubFids = array_keys($hassub);
		if ($fids = array_diff($hassubFids, $subFids)) {
			$dm = [];
			$dm['hassub'] = 1;
			$this->_getForum()->batchUpdateForum($fids, $dm, PwForum::FETCH_MAIN);
		}

		if ($fids = array_diff($subFids, $hassubFids)) {
			$dm = [];
			$dm['hassub'] = 0;
			$this->_getForum()->batchUpdateForum($fids, $dm, PwForum::FETCH_MAIN);
		}

		app(PwUserMiscService::class)->updateManager($allManager);
	}
	
	/**
	 * 重新统计所有版块的帖子数
	 */
	public function countAllForumStatistics() {
		$forums = $this->_getForum()->getForumOrderByType(false);
		$fids = array_keys($forums);

		$dm = [];
		$dm['threads'] = 0;
		$dm['posts'] = 0;
		$dm['article'] = 0;
		$dm['subThreads'] = 0;
		$this->_getForum()->batchUpdateForum($fids, $dm, PwForum::FETCH_STATISTICS);

		$threads = app(PwThreadExpand::class)->countThreadsByFid();
		$posts = app(PwThreadExpand::class)->countPostsByFid();

		foreach ($fids as $key => $value) {
			if (!isset($threads[$value]) && !isset($posts[$value])) continue;

			$dm = [];
			$dm['fid'] = $value;
			$dm['threads'] = $threads[$value]['sum'];
			$dm['posts'] = $posts[$value]['sum'];
			$this->_getForum()->updateForum($dm, PwForum::FETCH_STATISTICS);
		}
		foreach ($fids as $key => $value) {
			$this->_getForum()->updateForumStatistics($value);
		}
	}

	protected function _getForum() {
		return app(PwForum::class);
	}
}