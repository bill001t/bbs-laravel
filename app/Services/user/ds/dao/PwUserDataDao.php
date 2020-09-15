<?php

namespace App\Services\user\ds\dao;

use App\Services\user\ds\relation\userData;
use App\Services\user\ds\traits\userDataTrait;

use DB, Schema;

class PwUserDataDao extends userData
{
	use userDataTrait;

	/**
	 * 根据用户ID获得用户的数据
	 *
	 * @param int $uid 用户ID
	 * @return array
	 */
	public function getUserByUid($uid) {
		return self::find($uid);
	}

	/**
	 * 根据用户名获得用户信息
	 *
	 * @param string $username
	 * @return array
	 */
	public function getUserByName($username) {
		return self::where('username', $username)
			->get();
	}

	/**
	 * 根据用户email获得用户信息
	 *
	 * @param string $email
	 * @return array
	 */
	public function getUserByEmail($email) {
		return self::where('email', $email)
			->get();
	}

	/** 
	 * 根据用户ID列表获取ID
	 *
	 * @param array $uids
	 * @return array
	 */
	public function fetchUserByUid($uids) {
		return self::whereIn('uid', $uids)
            ->get();
	}

	/**
	 * 根据用户名列表批量获取用户信息
	 *
	 * @param array $usernames
	 * @return array
	 */
	public function fetchUserByName($usernames) {
		return self::whereIn('username', $usernames)
            ->get();
	}
	
	/** 
	 * 插入用户数据
	 *
	 * @param array $fields 用户数据
	 * @return int
	 */
	public function addUser($fields) {
		return self::create($fields);
	}
	
	/** 
	 * 根据用户ID更新用户数据
	 *
	 * @param int $uid 用户ID
	 * @param array $fields 用户数据
	 * @return boolean|int
	 */
	public function editUser($uid, $fields) {
		return self::where('uid', $uid)
        ->update($fields);
	}
	
	/** 
	 * 删除用户数据
	 *
	 * @param int $uid  用户ID
	 * @return int
	 */
	public function deleteUser($uid) {
		self::destroy($uid);
	}
	
	/** 
	 * 批量删除用户信息
	 *
	 * @param array $uids 用户ID
	 * @return int
	 */
	public function batchDeleteUser($uids) {
		self::destroy($uids);
	}
	
	/**
	 * 获得数据表结构
	 *
	 * @return array
	 */
	public function getDataStruct() {
		static $struct = array();

		if (!$struct) {
			$result = DB::Select('SHOW COLUMNS FROM ' . $this->table);

			foreach($result as $item){
				$struct[] = $item->field;
			}
		}

		return $struct;
	}

	/*对windiduserdao中的查询语句改写*/
	public function getStruct() {
        static $struct = array();

        if (!$struct) {
            $result = DB::Select('SHOW COLUMNS FROM ' . $this->table);

            foreach($result as $item){
                $struct[] = $item->field;
            }
        }

        return $struct;
	}

	/**
	 * 添加用户积分字段(>8以上的）
	 *
	 * @param int $num
	 * @return int
	 */
	public function alterAddCredit($num) {
		Schema::table($this->table, function($table) use($num){
			$table->integer(sprintf('credit%d', $num))
				->default(0);
		});
	}

	/**
	 * 删除用户积分字段（1-8不允许删除）
	 *
	 * @param int $num
	 * @return int
	 */
	public function alterDropCredit($num) {
		Schema::table($this->table, function($table) use($num){
			$table->dropColumn(sprintf('credit%d', $num));
		});
	}

	/**
	 * 清空用户的积分（只适用于1-8）
	 *
	 * @param int $num
	 * @return int
	 */
	public function clearCredit($num) {
		return self::where('uid', '>', 0)
			->update(sprintf('credit%d', $num), 0);
	}
}