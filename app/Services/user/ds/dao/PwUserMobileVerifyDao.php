<?php

namespace App\Services\user\ds\dao;

use App\Services\user\ds\relation\userMobileVerify;
use App\Services\user\ds\traits\userMobileVerifyTrait;

class PwUserMobileVerifyDao extends userMobileVerify
{
	use userMobileVerifyTrait;


	/**
	 * 取一条
	 *
	 * @param int $id
	 * @return array
	 */
	public function get($id) {
		return self::find($id);
	}
	
	/**
	 * 批量取
	 *
	 * @param array $ids
	 * @return array
	 */
	public function fetch($ids) {
		return self::whereIn('id', $ids);
	}
	
	/**
	 * 添加单条
	 * 
	 * @param array $fields
	 * @return bool 
	 */
	public function add($fields) {
		return self::create($fields);
	}
	
	/**
	 * 添加单条
	 * 
	 * @param array $fields
	 * @return bool 
	 */
	public function replace($fields) {
		return self::firstOrcreate($fields);
	}
	
	/**
	 * 删除单条
	 * 
	 * @param int $id
	 * @return bool 
	 */
	public function _delete($id) {
		return self::destroy($id);
	}
	
	/**
	 * 删除单条
	 * 
	 * @param int $id
	 * @return bool 
	 */
	public function deleteByExpiredTime($expired_time) {
		return self::where('expired_time', '<', $expired_time)
			->delete();
	}
	
	/**
	 * 批量删除
	 * 
	 * @param array $ids
	 * @return bool 
	 */
	public function batchDelete($ids) {
		return self::destroy($ids);
	}
	
	/**
	 * 更新单条
	 * 
	 * @param int $id
	 * @param array $fields
	 * @return bool 
	 */
	public function _update($id,$fields) {
		return self::where('id', $id)
			->update($fields);
	}
	
	/**
	 * 更新单条
	 * 
	 * @param int $expiredTime
	 * @param array $fields
	 * @return bool 
	 */
	public function updateByExpiredTime($expiredTime, $fields) {
		return self::where('expired_time', '<', $expiredTime)
			->update($fields);
	}
	
	/**
	 * 批量更新
	 * 
	 * @param array $ids
	 * @param array $fields
	 * @return bool 
	 */
	public function batchUpdate($ids,$fields) {
		return self::whereIn('id', $ids)
			->update($fields);
	}
}