<?php

namespace App\Services\Api\user\ds\dao;

use App\Core\BaseTrait;
use App\Services\Api\user\ds\relation\WindidUserInfo;

class WindidUserInfoDao extends WindidUserInfo
{
    use BaseTrait;

    public function getUserByUid($uid)
    {
        return self::find($uid);
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
     * @see WindidUserInterface::getUserByName()
     */
    public function getUserByName($username)
    {
        return self::where('username', $username)
            ->first();
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
     * @see WindidUserInterface::getUserByEmail()
     */
    public function getUserByEmail($email)
    {
        return self::where('email', $email)
            ->first();
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

    public function getUserInfoByUid($uid) {
        return self::find($uid);
    }
}