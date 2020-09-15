<?php

namespace App\Services\user\ds\dao;

use App\Services\user\ds\relation\user;
use App\Services\user\ds\traits\userTrait;

class PwUserDao extends user
{
	use userTrait;

	/**
	 * 根据用户ID获得用户的扩展数据
	 *
	 * @param int $uid 用户ID
	 * @return array
	 */
	public function getUserByUid($uid) {
		return self::find($uid);
	}
	
	/**
	 * 根据用户名获得用户信息
	 *
	 * @param string $username
	 * @return array
	 */
	public function getUserByName($username) {
		return self::where('username', $username)
			->first();
	}

	/**
	 * 根据用户的email获得用户信息
	 *
	 * @param string $email
	 * @return array
	 */
	public function getUserByEmail($email) {
		return self::where('email', $email)
			->first();
	}

	/** 
	 * 根据用户ID列表获取ID
	 *
	 * @param array $uids
	 * @return array
	 */
	public function fetchUserByUid($uids) {
		return self::whereIn('uid', $uids);
	}

	/**
	 * 根据用户名列表批量获得用户信息
	 *
	 * @param array $usernames
	 * @return array
	 */
	public function fetchUserByName($usernames) {
		return self::whereIn('username', $usernames);
	}

	/** 
	 * 插入用户扩展数据
	 *
	 * @param array $fields 用户数据
	 * @return boolean|Ambigous <number, boolean, rowCount>
	 */
	public function addUser($fields) {
		return self::create($fields);
	}

	/** 
	 * 根据用户ID更新用户扩展数据
	 *
	 * @param int $uid    用户ID
	 * @param array $fields  用户扩展数据
	 * @return boolean|int
	 */
	public function editUser($uid, $fields) {
		return self::where('uid', $uid)
			->update($fields);
	}

	/** 
	 * 删除用户数据
	 *
	 * @param int $uid  用户ID
	 * @return int
	 */
	public function deleteUser($uid) {
		return self::destroy($uid);
	}

	/** 
	 * 批量删除用户信息
	 *
	 * @param array $uids 用户ID
	 * @return int
	 */
	public function batchDeleteUser($uids) {
		return self::destroy($uids);
	}
}
?>