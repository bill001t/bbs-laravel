<?php

namespace App\Services\forum\bm;

use App\Services\forum\bs\PwOvertime;
use App\Services\forum\bs\PwThread;
use App\Services\forum\bs\PwSpecialSort;

class PwOvertimeService
{
	/**
	 * 貌似为从overtime中提取出tid对应的类型，可能该tid已经被topped，highlight了，分别获取，删除其中，再将threads表中的也对应更新为0，看来是贴数类型帖子，其中topped的要相应更新specialSort表
	 * @param $tid
     */
	public function updateOvertime($tid) {
		$overtimes = $this->_getOvertimeDs()->getOvertimeByTid($tid);
		$dm = [];
		if ($overtimes) {
			$timestamp = time();
			$newOvertime = 0;
			$ids = array();
			foreach ($overtimes as $v) {
				if ($v['overtime'] > $timestamp) {
					(!$newOvertime || $newOvertime > $v['overtime']) && $newOvertime = $v['overtime'];
				} else {
					switch ($v['m_type']) {
						case 'topped':
							$dm['topped'] = 0;
							app(PwSpecialSort::class)->deleteSpecialSortByTid($tid);
							break;
						case 'highlight':
							$dm['highlight'] = 0;
							break;
					}
					$ids[] = $v['id'];
				}
			}
			$ids && $this->_getOvertimeDs()->batchDelete($ids);
			$dm['overtime'] = $newOvertime;
		} else {
			$dm['overtime'] = 0;
		}
		$this->_getThreadDs()->updateThread($dm);
	}

	private function _getOvertimeDs() {
		return app(PwOvertime::class);
	}

	protected function _getThreadDs() {
		return app(PwThread::class);
	}
}