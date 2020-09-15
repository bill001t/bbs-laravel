<?php

namespace App\Services\Api\user\ds\dao;

use App\Core\BaseTrait;
use App\Services\Api\user\ds\relation\WindidUser;

class WindidUserAllDao extends WindidUser
{
    use BaseTrait;

    public function getUserByUid($uid)
    {
        return self::with('userData', 'userInfo')
            ->find($uid);
    }

    /* (non-PHPdoc)
     * @see WindidUserInterface::getUsersByUids()
     */
    public function fetchUserByUid($uids)
    {
        return self::with('userData', 'userInfo')
            ->whereIn('uid', $uids)
            ->get();
    }

    /* (non-PHPdoc)
     * @see WindidUserInterface::getUserByName()
     */
    public function getUserByName($username)
    {
        return self::with('userData', 'userInfo')
            ->where('username', $username)
            ->first();
    }

    /* (non-PHPdoc)
     * @see WindidUserInterface::getUsersByNames()
     */
    public function fetchUserByName($usernames)
    {
        return self::with('userData', 'userInfo')
            ->whereIn('username', $usernames)
            ->get();
    }

    /* (non-PHPdoc)
     * @see WindidUserInterface::getUserByEmail()
     */
    public function getUserByEmail($email)
    {
        return self::with('userData', 'userInfo')
            ->where('email', $email)
            ->first();
    }

    /* (non-PHPdoc)
     * @see WindidUserInterface::addUser()
     */
    public function addUser($fields)
    {
        $user = self::create($fields);
        $user->userData()->create($fields);
        $user->userInfo()->create($fields);

        return $user->{$this->primaryKey};
    }

    /* (non-PHPdoc)
     * @see WindidUserInterface::deleteUser()
     */
    public function deleteUser($uid)
    {
        self::destroy($uid);
        self::with('userData')
            ->userData()
            ->destory($uid);
        self::with('userInfo')
            ->userData()
            ->destory($uid);

        return true;
    }

    /* (non-PHPdoc)
     * @see WindidUserInterface::batchDeleteUser()
     */
    public function batchDeleteUser($uids)
    {
        self::destroy($uids);
        self::with('userData')
            ->userData()
            ->destory($uids);
        self::with('userInfo')
            ->userData()
            ->destory($uids);

        return true;
    }

    /* (non-PHPdoc)
     * @see WindidUserInterface::editUser()
     */
    public function editUser($uid, $fields, $increaseFields = array())
    {
        $user = self::where('uid', $uid)
            ->_update_($uid, $fields, $increaseFields);
        $user->userData()->_update_($uid, $fields, $increaseFields);
        $user->userInfo()->_update_($uid, $fields, $increaseFields);

        return $user;
    }
}