<?php

namespace App\Services\user\ds\dao;

use App\Services\user\ds\relation\userRegisterCheck;
use App\Services\user\ds\traits\userRegisterCheckTrait;

class PwUserRegisterCheckDao extends userRegisterCheck
{
	use userRegisterCheckTrait;


	/**
	 * 根据用户ID获得用户的状态信息
	 *
	 * @param int $uid
	 * @return array
	 */
	public function getInfo($uid) {
		return self::find($uid);
	}
	
	/**
	 * 根据用户的审核状态获得用户的记录
	 *
	 * @param int $ifchecked
	 * @param int $limit
	 * @param int $start
	 * @return array
	 */
	public function getInfoByIfchecked($ifchecked, $perpage) {
		return self::where('ifchecked', $ifchecked)
			->paginate($perpage);
	}
	
	/** 
	 * 获得没有激活用户的统计总数
	 *
	 * @param int $ifchecked
	 * @return int
	 */
	public function countByIfchecked($ifchecked) {
        return self::where('ifchecked', $ifchecked)
            ->count();
	}
	
	/**
	 * 根据用户激活字段获得用户的记录
	 *
	 * @param int $ifactived
	 * @param int $limit
	 * @param int $start
	 * @return array
	 */
	public function getInfoByIfactived($ifactived, $perpage) {
        return self::where('ifactived', $ifactived)
            ->paginate($perpage);
	}
	
	/** 
	 * 获得没有激活用户的统计总数
	 *
	 * @param int $ifactived
	 * @return int
	 */
	public function countByIfactived($ifactived) {
        return self::where('ifactived', $ifactived)
            ->count();
	}
	
	/**
	 * 设置用户的状态
	 *
	 * @param int $uid 用户ID
	 * @param int $ifchecked 用户是否已审核
	 * @param int $ifactived 用户是否已经激活
	 * @return boolean
	 */
	public function addInfo($uid, $ifchecked, $ifactived) {
        return self::firstOrCreate(['uid' => $uid, 'ifchecked' => $ifchecked, 'ifactived' => $ifactived]);
	}
	
	/**
	 * 更新用户的状态
	 *
	 * @param int $uid 用户ID
	 * @param array $data 用户信息
	 * @return boolean
	 */
	public function updateInfo($uid, $data) {
        return self::where('uid', $uid)
            ->update($data);
	}
	
	/**
	 * 批量修改用户
	 *
	 * @param array $uids
	 * @param array $data
	 * @return boolean
	 */
	public function batchUpdateInfo($uids, $data) {
        return self::whereIn('uid', $uids)
            ->update($data);
	}
	
	/**
	 * 根据用户ID删除该用户的状态信息
	 *
	 * @param int $uid
	 * @return boolean
	 */
	public function deleteInfo($uid) {
        return self::destroy($uid);
	}
	
	/**
	 * 根据用户ID批量删除用户状态记录信息
	 *
	 * @param array $uids
	 * @return boolean
	 */
	public function batchDeleteInfo($uids) {
        return self::destroy($uids);
	}
}