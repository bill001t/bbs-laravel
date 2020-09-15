<?php

namespace App\Services\user\ds\dao;

use App\Services\user\ds\relation\userMobile;
use App\Services\user\ds\traits\userMobileTrait;

class PwUserMobileDao extends userMobile
{
	 use userMobileTrait;


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
		return self::whereIn('id', $ids)
			->get();
	}

	/**
	 * 根据手机号码取一条
	 *
	 * @param int $mobile
	 * @return array
	 */
	public function getByMobile($mobile) {
		return self::where('mobile', $mobile)
			->first();
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
	 * 删除单条
	 * 
	 * @param int $id
	 * @return bool 
	 */
	public function _delete($id) {
		return self::destroy($id);
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
		return self::where('id', $ids)
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