<?php

namespace App\Services\user\ds\dao;

use App\Services\user\ds\relation\userRegisterIp;
use App\Services\user\ds\traits\userRegisterIpTrait;

class PwUserRegisterIpDao extends userRegisterIp
{
	use userRegisterIpTrait;

	/** 
	 * 根据IP查询数据
	 *
	 * @param string $ip ip地址
	 * @return array
	 */
	public function get($ip) {
		return self::where('ip', $ip)
			->get();
	}

	/** 
	 * 跟新某个IP的数据
	 *
	 * @param string $ip IP
	 * @param int $date 日期
	 * @return int
	 */
	public function _update($ip, $date) {
		return DB::update('REPLACE INTO ' . $this->table . ' SET `num`=`num`+1,`ip`=?,`last_regdate`=?', [$ip, $date]);
	}
}