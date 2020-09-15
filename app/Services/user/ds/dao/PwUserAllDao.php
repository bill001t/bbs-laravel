<?php

namespace App\Services\user\ds\dao;

use App\Services\user\ds\relation\user;
use App\Services\user\ds\traits\userTrait;
use DB;

class PwUserAllDao extends user
{
    use userTrait;

    protected $_dataTable = '_user_data';
    protected $_infoTable = '_user_info';

    public function getUserByUid($uid)
    {
        return self::leftJoin($this->_dataTable . ' as d', $this->table . '.uid', '=', 'd.uid')
            ->leftJoin($this->_infoTable . ' as i', $this->table . '.uid', '=', 'i.uid')
            ->select(DB::raw($this->table . '.*, d.*, i.*'))
            ->where($this->table . '.uid', $uid)
            ->first();
    }

    public function getUserByName($username)
    {
        return self::leftJoin($this->_dataTable . ' as d', $this->table . '.uid', '=', 'd.uid')
            ->leftJoin($this->_infoTable . ' as i', $this->table . '.uid', '=', 'i.uid')
            ->select(DB::raw($this->table . '.*, d.*, i.*'))
            ->where($this->table . '.username', $username)
            ->first();
    }

    public function getUserByEmail($email)
    {
        return self::leftJoin($this->_dataTable . ' as d', $this->table . '.uid', '=', 'd.uid')
            ->leftJoin($this->_infoTable . ' as i', $this->table . '.uid', '=', 'i.uid')
            ->select(DB::raw($this->table . '.*, d.*, i.*'))
            ->where($this->table . '.email', $email)
            ->first();
    }

    public function fetchUserByUid($uids)
    {
        return self::leftJoin($this->_dataTable . ' as d', $this->table . '.uid', '=', 'd.uid')
            ->leftJoin($this->_infoTable . ' as i', $this->table . '.uid', '=', 'i.uid')
            ->select(DB::raw($this->table . '.*, d.*, i.*'))
            ->whereIn($this->table . '.uid', $uids)
            ->get();
    }

    public function fetchUserByName($usernames)
    {
        return self::leftJoin($this->_dataTable . ' as d', $this->table . '.uid', '=', 'd.uid')
            ->leftJoin($this->_infoTable . ' as i', $this->table . '.uid', '=', 'i.uid')
            ->select(DB::raw($this->table . '.*, d.*, i.*'))
            ->whereIn($this->table . '.username', $usernames)
            ->get();
    }

    public function addUser($fields)
    {
        $user = self::create($fields);
        $user->userData()->create($fields);
        $user->userInfo()->create($fields);

        return $user;
    }

    public function editUser($uid, $fields)
    {
        $user = self::where('uid', $uid)
            ->update($fields);
        $user->userData()->_update($fields);
        $user->userInfo()->_update($fields);

        return $user;
    }

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
}

?>