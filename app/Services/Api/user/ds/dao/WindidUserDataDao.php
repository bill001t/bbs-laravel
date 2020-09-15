<?php

namespace App\Services\Api\user\ds\dao;

use App\Core\BaseTrait;
use App\Services\Api\user\ds\relation\WindidUserData;
use DB;

/**
 * 用户积分信息数据访问层
 *
 * @author Jianmin Chen <sky_hold@163.com>
 * @license http://www.phpwind.com
 * @version $Id: WindidUserDataDao.php 24810 2013-02-21 10:32:03Z jieyin $
 * @package windid.service.user.dao
 */
class WindidUserDataDao extends WindidUserData
{
    use BaseTrait;

    public function getUserByUid($uid)
    {
        return self::find($uid);
    }

    /* (non-PHPdoc)
     * @see WindidUserInterface::getUserByName()
     */
    public function getUserByName($username)
    {
        return self::where('username', $username)
            ->first();
    }

    /* (non-PHPdoc)
     * @see WindidUserInterface::getUserByEmail()
     */
    public function getUserByEmail($email)
    {
        return self::where('email', $email)
            ->first();
    }

    /* (non-PHPdoc)
     * @see WindidUserInterface::getUsersByUids()
     */
    public function fetchUserByUid($uids)
    {
        return self::whereIn('uid', $uids)
            ->get();
    }

    /* (non-PHPdoc)
     * @see WindidUserInterface::getUsersByNames()
     */
    public function fetchUserByName($usernames)
    {
        return self::whereIn('username', $usernames)
            ->get();
    }

    /* (non-PHPdoc)
     * @see WindidUserInterface::addUser()
     */
    public function addUser($fields)
    {
        return self::create($fields);
    }

    /* (non-PHPdoc)
     * @see WindidUserInterface::deleteUser()
     */
    public function deleteUser($uid)
    {
        return self::destroy($uid);
    }

    /* (non-PHPdoc)
     * @see WindidUserInterface::batchDeleteUser()
     */
    public function batchDeleteUser($uids)
    {
        return self::destroy($uids);
    }

    /* (non-PHPdoc)
     * @see WindidUserInterface::editUser()
     */
    public function editUser($uid, $fields, $increaseFields = array())
    {
        return self::_update_($uid, $fields, $increaseFields);
    }

    /**
     * 获取用户的积分
     *
     * @param int $uid
     * @return array
     */
    public function getCredit($uid)
    {
        return self::find($uid);
    }

    /**
     * 更新用户的积分
     *
     * @param int $uid
     * @param array $fields
     * @param array $increaseFields
     * @return int
     */
    public function updateCredit($uid, $fields, $increaseFields = array())
    {
        return self::_update_($uid, $fields, $increaseFields);
    }

    /**
     * 获得表结构
     *
     * @return array
     */
    public function getStruct()
    {
        static $struct = array();

        if (!$struct) {
            $result = DB::Select('SHOW COLUMNS FROM ' . $this->table);

            foreach ($result as $item) {
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
    public function alterAddCredit($num)
    {
        Schema::table($this->table, function ($table) use ($num) {
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
    public function alterDropCredit($num)
    {
        Schema::table($this->table, function ($table) use ($num) {
            $table->dropColumn(sprintf('credit%d', $num));
        });
    }

    /**
     * 清空用户的积分（只适用于1-8）
     *
     * @param int $num
     * @return int
     */
    public function clearCredit($num)
    {
        return self::where('uid', '>', 0)
            ->update(sprintf('credit%d', $num), 0);
    }

    /**
     * 获得数据表结构
     *
     * @return array
     */
    public function getDataStruct()
    {
        static $struct = array();

        if (!$struct) {
            $result = DB::Select('SHOW COLUMNS FROM ' . $this->table);

            foreach ($result as $item) {
                $struct[] = $item->field;
            }
        }

        return $struct;
    }
}