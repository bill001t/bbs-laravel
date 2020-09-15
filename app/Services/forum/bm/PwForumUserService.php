<?php

namespace App\Services\forum\bs;

use App\Services\forum\bs\PwThreadExpand;
use App\Core\Tool;

/**
 * 版块会员公共服务
 */

class PwForumUserService {
	
	/**
	 * 获取版块活跃用户
	 *
	 * @param int $fid
	 * @param int $day
	 * @param int $num
	 * @return array
	 */
	public function getActiveUser($fid, $day = 7, $num = 12) {
		$key = "active_user_{$fid}_{$day}_{$num}";
		if (!$result = Core::cache()->get($key)) {
			$result = $this->_getActiveUser($fid, $day, $num);
			Core::cache()->set($key, $result, array(), 3600);
		}
		return $result;
	}

	protected function _getActiveUser($fid, $day, $num) {
		$time = Tool::getTime() - ($day * 86400);
		$array = array();
		$thread = app(PwThreadExpand::class)->countUserThreadByFidAndTime($fid, $time, $num);
		$post = app(PwThreadExpand::class)->countUserPostByFidAndTime($fid, $time, $num);
		foreach ($thread as $key => $value) {
			if (!$key) continue;
			$array[$key] = $value['count'];
		}
		foreach ($post as $key => $value) {
			if (!$key) continue;
			if (isset($array[$key])) {
				$array[$key] += $value['count'];
			} else {
				$array[$key] = $value['count'];
			}
		}
		arsort($array);
		return array_slice($array, 0, $num, true);
	}
}