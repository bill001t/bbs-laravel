<?php

namespace App\Services\user\ds\dao;

use App\Services\user\ds\relation\userActiveCode;
use App\Services\user\ds\traits\userActiveCodeTrait;

class PwUserActiveCodeDao extends userActiveCode
{
	use userActiveCodeTrait;

	/** 
	 * 添加用户激活码
	 *
	 * @param array $data 激活码相关数据
	 * @return boolean|int
	 */
	public function insert($data) {
		return self::firstOrCreate($data);
	}
	
	/** 
	 * 更新用户激活码
	 *
	 * @param int $uid 用户ID
	 * @param int $activetime 激活时间
	 * @return boolean|int
	 */
	public function _update($uid, $activetime) {
		return self::where('uid', $uid)
			->update(['active_time' => $activetime]);
	}
	
	/** 
	 * 根据用户ID删除信息
	 *
	 * @param int $uid 用户ID
	 * @return int|boolean
	 */
	public function deleteByUid($uid) {
		return self::destroy($uid);
	}
	
	/** 
	 * 根据用户ID获得信息
	 *
	 * @param int $uid 用户ID
	 * @param int $typeid 激活码类型
	 * @return array
	 */
	public function getInfoByUid($uid, $typeid) {
		return self::where('uid', $uid)
			->where('typeid', $typeid)
			->get();
	}
}