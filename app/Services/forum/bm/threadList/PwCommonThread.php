<?php
namespace App\Services\forum\bm\threadList;

use App\Services\forum\bm\threadList\PwThreadDataSource;
use App\Services\forum\bs\PwThread;
use App\Services\forum\bs\PwSpecialSort;

use Input;

/**
 * 帖子列表数据接口 / 普通列表
 *
 * @author Jianmin Chen <sky_hold@163.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: PwCommonThread.php 16394 2012-08-23 06:28:06Z long.shi $
 * @package forum
 */

class PwCommonThread extends PwThreadDataSource
{
	protected $forum;
	protected $specialSortTids;
	protected $count;

	public function __construct($forum) {
		$this->forum = $forum;
		$this->specialSortTids = $this->_getSpecialSortDs()->getSpecialSortByFid($forum->fid)->pluck('tid')->all();
		$this->count = count($this->specialSortTids);
	}

	public function getTotal() {
		return $this->forum->foruminfo['threads'] + $this->count;
	}

	public function getData($perpage, $page = '') {
		$threaddb = array();
		if ($page * 10 < $this->count) {
			$array = $this->_getThreadDs()->fetchThreadByTid($this->specialSortTids, 10);
			foreach ($array as $key => $value) {
				$value['issort'] = true;
				$threaddb[] = $value;
			}
		}

		$array = $this->_getThreadDs()->getThreadByFid($this->forum->fid, $perpage);
		foreach ($array as $key => $value) {
			$threaddb[] = $value;
		}

		return $threaddb;
	}

	protected function _getThreadDs() {
		return app(PwThread::class);
	}

	protected function _getSpecialSortDs() {
		return app(PwSpecialSort::class);
	}
}