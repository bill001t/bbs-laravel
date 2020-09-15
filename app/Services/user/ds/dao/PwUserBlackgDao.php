<?php

namespace App\Services\user\ds\dao;

use App\Services\user\ds\relation\userBlack;
use App\Services\user\ds\traits\userBlackTrait;

class PwUserBlackDao extends userBlack
{
	use userBlackTrait;


	/**
	 * 获取单条
	 *
	 * @param int $uid
	 * @return array
	 */
	public function getBlacklist($uid) {
		return self::find($uid);
	}

	/**
	 * 获取单条
	 *
	 * @param array $uids
	 * @return array
	 */
	public function fetchBlacklist($uids) {
		return self::whereIn('uid', $uids)
			->get();
	}

	/**
	 * 更新
	 *
	 * @param array $blacklist(serialized array)
	 * @return bool
	 */
	public function replaceBlacklist($data) {
		return self::firstOrCreate($data);
	}

	public function addBlack($uid, $blackUid) {
		$uid = intval($uid);
		$blackUid = intval($blackUid);
		if ($uid < 1 || $blackUid < 1) return false;
		$blackList = $this->getBlacklist($uid);
		if (in_array($blackUid, $blackList)) {
			return true;
		}
		$blackList[] = $blackUid;
		return $this->setBlacklist($uid, $blackList);
	}

	public function setBlacklist($uid, $blackList) {
		if (!is_array($blackList)) return false;
		$data['uid'] = $uid;
		$data['blacklist'] = serialize($blackList);
		return $this->replaceBlacklist($data);
	}
	/**
	 * 删除
	 *
	 * @param int $uid
	 * @return bool
	 */
	public function deleteBlacklist($uid) {
		return $this->destroy($uid);
	}
}