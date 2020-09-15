<?php

namespace App\Services\usergroup\ds\dao;

use App\Services\usergroup\ds\relation\userPermissionGroups;

class PwUserPermissionGroupsDao extends userPermissionGroups
{

	/**
	 * 
	 * 设置用户组权限
	 *
	 * @param array $fields
	 */
	function setGroupPermission($fields) {
		return self::firstOrCreate($fields);
	}
	
	/**
	 * 获取某会员组的权限
	 *
	 * @param string $gid
	 * @param array $keys
	 */
	public function getPermissions($gid, $keys = array()) {
		$sql = self::where('gid', $gid);
        ! empty($keys) && ($sql = $sql->whereIn('rkey', $keys));

        return $this->_format($sql->get());
	}

	public function getPermissionByRkey($rkey) {
		return $this->_format(self::where('rkey', $rkey)
			->get());
	}

	public function getPermissionByRkeyAndGids($rkey, $gids) {
		return $this->_format(self::where('rkey', $rkey)
			->whereIn('gid', $gids)
			->get());
	}
	
	/**
	 * 获取某类rkey的权限
	 *
	 * @param string $rkeys
	 * @param array
	 */
	public function fetchPermissionByRkey($rkeys) {
		return $this->_format(self::whereIn('rkey', $rkeys)
			->get());
	}
	
	public function fetchPermissionByGid($gids) {
		return $this->_format(self::whereIn('gid', $gids)
			->get());
	}
	
	/**
	 * 删除某用户组所有权限
	 *
	 * @param int $gid
	 */
	public function deletePermissionsByGid($gid){
		return self::where('gid', $gid)
			->delete();
	}
	
	/**
	 * 删除某用户组所有权限
	 *
	 * @param int $gid
	 * @param array $keys
	 */
	public function batchDeletePermissionByGidAndKeys($gid, $keys) {
		return self::where('gid', $gid)
			->whereIn('rkey', $keys)
			->delete();
	}

	protected function _format($result) {
		foreach ($result as $key => $value) {
			$value['vtype'] == 'array' && $value['rvalue'] = unserialize($value['rvalue']);
			$result[$key] = $value;
		}
		return $result;
	}
}