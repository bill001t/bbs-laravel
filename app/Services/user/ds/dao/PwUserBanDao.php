<?php

namespace App\Services\user\ds\dao;

use App\Services\user\ds\relation\userBan;
use App\Services\user\ds\traits\userBanTrait;

use DB;

class PwUserBanDao extends userBan
{
	use userBanTrait;


	/**
	 * 获取用户ID禁止信息
	 *
	 * @param int $uid
	 * @return array
	 */
	public function getBanInfo($uid) {
		return self::where('uid', $uid)
            ->get();
	}
	
	/**
	 * 根据用户的禁止类型获取用户iD禁止信息
	 *
	 * @param int $uid 用户ID
	 * @param int $typeid 禁止类型
	 * @return array
	 */
	public function getBanInfoByTypeid($uid, $typeid) {
        return DB::select('SELECT * FROM '. $this->table . ' WHERE uid=? AND `typeid` & ?', [$uid, $typeid]);
	}
	
	/**
	 * 根据禁止类型及禁止类型中的具体ID获得用户uid的禁止信息
	 *
	 * @param int $uid
	 * @param int $typeid
	 * @param int $fid
	 * @return array
	 */
	public function getBanInfoByTypeidAndFid($uid, $typeid, $fid) {
        return DB::select('SELECT * FROM '. $this->table . ' WHERE uid=? AND `typeid` & ? AND `fid` IN (0, ?)', [$uid, $typeid, $fid]);
	}
	
	/**
	 * 根据用户ID列表及版块ID获得用户禁止信息
	 *
	 * @param array $uids
	 * @param int $typeid 用户禁止类型
	 * @return array
	 */
	public function fetchBanInfoByUid($uids, $typeid) {
        return DB::select('SELECT * FROM ' . $this->table . ' WHERE uid IN (?) AND `typeid` & ?', [implode(',', $uids), $typeid]);
	}
	
	/**
	 * 根据禁止ID列表获取禁止数据
	 *
	 * @param array $ids
	 * @return array
	 */
	public function fetchBanInfo($ids) {
        return self::whereIn('id', $ids)
            ->get();
	}
	
	/** 
	 * 添加用户禁止记录
	 *
	 * @param array $data 禁言信息
	 * @return int
	 */
	public function addBanInfo($data) {
        return self::firstOrCreate($data);
	}
	
	/**
	 * 批量禁止用户
	 *
	 * @param array $data
	 * @return array
	 */
	public function batchAddBanInfo($data) {
		$clear = array();
		foreach ($data as $key => $_item) {
			$_temp = array();
			$_temp['uid'] = empty($_item['uid']) ? '' : $_item['uid'];
			$_temp['typeid'] = empty($_item['typeid']) ? '' : $_item['typeid'];
			$_temp['fid'] = empty($_item['fid']) ? '' : $_item['fid'];
			$_temp['end_time'] = empty($_item['end_time']) ? '' : $_item['end_time'];
			$_temp['created_time'] = empty($_item['created_time']) ? '' : $_item['created_time'];
			$_temp['created_userid'] = empty($_item['created_userid']) ? '' : $_item['created_userid'];
			$_temp['reason'] = empty($_item['reason']) ? '' : $_item['reason'];
			$clear[] = $_temp;
		}

        if(empty($clear)){
            return false;
        }

		foreach($clear as $v){
			self::firstOrCreate($v);
		}

		return true;
	}
	
	/**
	 * 根据禁止ID列表删除禁止记录
	 *
	 * @param array $ids 
	 * @return boolean
	 */
	public function batchDelete($ids) {
		return self::destroy($ids);
	}

	/**
	 * 根据用户ID删除用户的屏蔽信息
	 *
	 * @param int $uid
	 * @return int
	 */
	public function deleteByUid($uid) {
        return self::where('uid', $uid)
            ->delete();
	}
	
	/** 
	 * 根据用户ID批量删除该用户信息
	 *
	 * @param array $uids 用户ID列表
	 * @return int
	 */
	public function batchDeleteByUids($uids) {
        return self::whereIn('uid', $uids)
            ->delete();
	}
	
	/**
	 * 根据条件统计数据
	 *
	 * @param array $condition
	 * @return int
	 */
	public function countByCondition($condition) {
        $user = self::whereRaw('1 = 1');
        $user = $this->_buildCondition($user, $condition);

        return $user->count();
	}
	
	/**
	 * 根据条件检索数据
	 *
	 * @param array $condition 查询条件
	 * @param int $limit 返回条数
	 * @param int $start 记录查询开始
	 * @return array
	 */
	public function fetchBanInfoByCondition($condition, $perpage) {
        $user = self::whereRaw('1 = 1');
        $user = $this->_buildCondition($user, $condition);

        return $user->paginate($perpage);

	}
	
	/**
	 * 构建搜索条件
	 * 搜索条件支持：
	 * <pre>
	 *   array('username/uid' => '', 'created_userid' => '', 'start_time' => '时间戳', 'end_time' => '时间戳');
	 * </pre>
	 *
	 * @param array $condition
	 * @return string
	 */
	private function _buildCondition($user, $condition) {
		foreach ($condition as $key => $value) {
			if (!$value && $value !== 0) continue;
			switch ($key) {
				case 'uid':
					$user = $user->where('uid', $value);
					break;
				case 'created_userid':
                    $user = $user->where('created_userid', $value);
					break;
				case 'start_time':
                    $user = $user->where('created_time', '>=', $value);
					break;
				case 'end_time':
                    $user = $user->where('end_time', '<=', $value);
					break;
			}
		}

        return $user;
	}
}