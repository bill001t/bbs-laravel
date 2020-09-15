<?php

namespace App\Services\usergroup\ds\dao;

use App\Services\usergroup\ds\relation\userGroups;

class PwUserGroupsDao extends userGroups
{

	/**
	 * 获取所有用户组
	 */
	public function getAllGroups() {
        return self::all();
	}
	
	/**
	 * 获取一个会员组详细信息
	 *
	 * @param int $gid
	 * @return Array
	 */
	public function getGroupByGid($gid) {
		return self::find($gid);
	}
	
	/**
	 * 根据一组gid获取用户组
	 * 
	 * @param array $gids
	 * @return array
	 */
	public function fetchGroup($gids) {
		return self::whereIn('gid', $gids)
            ->get();
	}
	
	/**
	 * 添加用户组
	 *
	 * @param array $fields
	 */
	public function addGroup($fields) {
		return self::create($fields);
	}
	
	/**
	 * 更新用户组
	 *
	 * @param int $gid
	 * @param array $fields
	 */
	public function updateGroup($gid, $fields) {
		return self::where('gid', $gid)
            ->update($fields);
	}
	
	/**
	 * 删除用户组
	 *
	 * @param int $gid
	 */
	public function deleteGroup($gid) {
		return self::destory($gid);
	}
	
	/**
	 * 按会员组类型获取组列表
	 *
	 * @param string $groupType
	 */
	public function getGroupsByType($groupType) {
		return self::where('type', $groupType)
            ->get();
	}
	
	/**
	 * 按会员组类型获取组列表（按升级点数升序）
	 *
	 * @param string $groupType
	 */
	public function getGroupsByTypeInUpgradeOrder($groupType) {
        return self::where('type', $groupType)
            ->orderby('points', 'asc')
            ->get();
	}
}