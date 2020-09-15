<?php

namespace App\Services\user\ds\dao;

use App\Services\user\ds\relation\userBelong;
use App\Services\user\ds\traits\userBelongTrait;

class PwUserBelongDao extends userBelong
{

	use userBelongTrait;

	
	/** 
	 * 获得某个用户的所有拥有的组
	 *
	 * @param int $uid 用户ID
	 * @return array
	 */
	public function getByUid($uid) {
		return self::find($uid);
	}

	public function getByGid($gid) {
		return self::where('gid', $gid);
	}
	
	/** 
	 * 根据用户ID列表获取ID
	 *
	 * @param array $uids
	 * @return array
	 */
	public function fetchUserByUid($uids) {
		return self::whereIn('uid', $uids)
			->get();
	}
	
	/** 
	 * 删除用户数据
	 *
	 * @param int $uid 用户ID
	 * @return boolean|int
	 */
	public function deleteByUid($uid) {
		return self::destroy($uid);
	}
	
	/** 
	 * 更新用户组信息
	 *
	 * @param int $uid 用户ID
	 * @param array $fields 用户数据
	 * @return boolean|int
	 */
	public function edit($uid, $fields) {
		$fields = ['uid' => $uid] + $fields;

		return self::firstOrCreate($fields);
	}
	
	/**
	 * 批量删除用户信息
	 *
	 * @param array $uids 用户ID
	 * @return boolean
	 */
	public function batchDeleteByUids($uids) {
		return self::destroy($uids);
	}
}