<?php

namespace App\Services\user\ds\dao;

use App\Services\user\ds\relation\userInfo;
use App\Services\user\ds\traits\userInfoTrait;

class PwUserInfoDao extends userInfo
{
    use userInfoTrait;


    /**
     * 根据用户ID获得用户信息
     *
     * @param int $uid 用户ID
     * @return array
     */
    public function getUserByUid($uid)
    {
        return self::find($uid);
    }

    /**
     * 根据用户名获得用户信息
     *
     * @param string $username
     * @return array
     */
    public function getUserByName($username)
    {
        return self::where('username', $username)
            ->get();
    }

    /**
     * 根据用户email获得用户信息
     *
     * @param string $email
     * @return array
     */
    public function getUserByEmail($email)
    {
        return self::where('email', $email)
            ->get();
    }

    /**
     * 根据用户ID列表批量获得用户信息
     *
     * @param array $uids
     * @return array
     */
    public function fetchUserByUid($uids)
    {
        return self::whereIn('uid', $uids)
            ->get();
    }

    /**
     * 根据用户名列表批量获得用户信息
     *
     * @param array $usernames
     * @return array
     */
    public function fetchUserByName($usernames)
    {
        return self::whereIn('username', $usernames)
            ->get();
    }

    /**
     * 添加用户资料
     *
     * @param array $fields 用户数据信息
     * @return int
     */
    public function addUser($fields)
    {
        return $user = self::create($fields);

    }

    /**
     * 更新用户信息
     *
     * @param int $uid 用户ID
     * @param array $fields 用户信息数据
     * @return int|boolean
     */
    public function editUser($uid, $fields, $increaseFields = array(), $bitFields = array())
    {
        return self::where('uid', $uid)
            ->update($fields);

    }

    /**
     * 删除用户数据
     *
     * @param int $uid 用户ID
     * @return int
     */
    public function deleteUser($uid)
    {
        return self::destroy($uid);
    }

    /**
     * 批量删除用户信息
     *
     * @param array $uids 用户ID
     * @return int
     */
    public function batchDeleteUser($uids)
    {
        return self::destroy($uids);
    }
}