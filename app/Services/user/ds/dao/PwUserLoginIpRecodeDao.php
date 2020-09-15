<?php

namespace App\Services\user\ds\dao;

use App\Services\user\ds\relation\userLoginIpRecode;
use App\Services\user\ds\traits\userLoginIpRecodeTrait;

class PwUserLoginIpRecodeDao extends userLoginIpRecode
{
	use userLoginIpRecodeTrait;

	
	/**
	 * 添加一次记录
	 *
	 * @param array $data
	 * @return boolean
	 */
	public function _update($data) {
		return self::_replace_($data);
	}
	
	/**
	 * 获得记录
	 *
	 * @param string $ip 登录Ip
	 * @return array
	 */
	public function get($ip) {
		return self::where('ip', $ip)
			->first();
	}
}