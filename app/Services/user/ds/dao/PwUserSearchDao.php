<?php

namespace App\Services\user\ds\dao;

use App\Services\user\ds\relation\user;
use App\Services\user\ds\traits\userTrait;

use DB;

class PwUserSearchDao extends user
{
    use userTrait;
	/**
	 * 根据查询条件查询用户数据
	 *
	 * @param array $condition
	 * @param int $limit 
	 * @param int $start
	 * @param array $orderby
	 * @return array
	 */
	public function searchUser($condition, $perpage, $orderby) {
        $user = DB::table($this->table)
            ->leftJoin('pw_user_data', 'pw_user.uid', '=', 'pw_user_data.uid')
            ->leftJoin('pw_user_info', 'pw_user.uid', '=', 'pw_user_info.uid');

        $user = $this->_buildCondition($user, $condition);
        $user = $this->_buildOrderby($user, $orderby);

        return $user->paginate($perpage);
	}
	
	/**
	 * 根据查询条件统计
	 *
	 * @param array $condition
	 * @return int
	 */
	public function countSearchUser($condition) {
        $user = DB::table($this->table)
            ->leftJoin('pw_user_data', 'pw_user.uid', '=', 'pw_user_data.uid')
            ->leftJoin('pw_user_info', 'pw_user.uid', '=', 'pw_user_info.uid');

        $user = $this->_buildCondition($user, $condition);

        return $user->count();
	}
	
	/**
	 * 总是获取相关三张表的所有数据
	 * 门户数据获取
	 *
	 * @param array $condition
	 * @param int $limit
	 * @param int $start
	 * @param array $orderby
	 */
	public function searchUserAllData($condition, $perpage, $orderby) {
        $user = DB::table($this->table)
            ->leftJoin('pw_user_data', 'pw_user.uid', '=', 'pw_user_data.uid')
            ->leftJoin('pw_user_info', 'pw_user.uid', '=', 'pw_user_info.uid');

        $user = $this->_buildCondition($user, $condition);
        $user = $this->_buildOrderby($user, $orderby);

        return $user->paginate($perpage);
	}
	
	/**
	 * 组装查询信息
	 *
	 * @param array $condition
	 * @return string
	 */
	private function _buildCondition($user, $condition) {
		foreach ($condition as $k => $v) {
			if ($v != 0 && !$v) continue;
			switch ($k) {
				case 'username':
					$user->where('pw_user.username', 'like', $v . '%');
					break;
				case 'uid':
					if (is_array($v)) {
						$user->whereIn('pw_user.uid', $v);
					} else {
						$user->where('pw_user.uid', $v);
					}
					break;
				case 'email':
					$user->where('pw_user.email', 'like', $v . '%');
					break;
				case 'gid':
					$user->whereIn('pw_user.groupid', (array)$v);
					break;
				case 'memberid':
					$user->whereIn('pw_user.memberid', (array)$v);
					break;
				case 'regdate':
					$user->where('pw_user.regdate', '>=', $v);
					break;
				case 'gender':
					$user->where('userInfo.gender', $v);
					break;
				case 'location':
					$user->where('userInfo.location', $v);
					break;
				case 'hometown':
					$user->where('userInfo.hometown', $v);
					break;
				default:
					break;
			}
		}
		return $user;
	}
	
	/**
	 * 构建orderBy
	 *
	 * @param array $orderby
	 * @return array
	 */
	protected function _buildOrderby($user, $orderby) {
		foreach ($orderby as $key => $value) {
			switch ($key) {
				case 'postnum':
                    $user = $user->orderby('pw_user_data.postnum', ($value ? 'DESC' : 'ASC'));
					break;
				case 'lastvisit':
                    $user->orderby('pw_user_data.lastvisit', ($value ? 'ASC' : 'DESC'));
					break;
				case 'lastpost':
                    $user->orderby('pw_user_data.lastpost', ($value ? 'ASC' : 'DESC'));
                    break;
				case 'regdate':
                    $user->orderby('pw_user.regdate', ($value ? 'ASC' : 'DESC'));
                    break;
			}
		}
		return $user;
	}
}