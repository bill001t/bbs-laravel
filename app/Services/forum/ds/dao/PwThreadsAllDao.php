<?php

namespace App\Services\forum\ds\dao;

use App\Services\forum\ds\relation\threads;
use App\Services\forum\ds\traits\threadsTrait;
use DB;

class PwThreadsAllDao extends threads
{
    use threadsTrait;

    public function getThread($tid)
    {
        return self::with('threadsContent')
            ->find($tid);
    }

    public function fetchThread($tids)
    {
        return self::with('threadsContent')
            ->whereIn('tid', $tids);
    }

    public function getThreadByFid($fid, $perpage)
    {
        return self::with('threadsContent')
            ->where('fid', $fid)
            ->paginate($perpage);
    }

    public function getThreadByUid($uid, $perpage)
    {
        return self::with('threadsContent')
            ->where('uid', $uid)
            ->paginate($perpage);
    }

    public function getThreadsByFidAndUids($fid, $uids, $perpage)
    {
        return self::with('threadsContent')
            ->where('fid', $fid)
            ->whereIn('uid', $uids)
            ->paginate($perpage);
    }

    public function addThread($fields)
    {
        $thread = self::create($fields);
        $thread->threadsContent()->create($fields);

        return true;
    }

    public function updateThread($tid, $fields, $increaseFields = array(), $bitFields = array())
    {
        app(PwThreadsContentDao::class)->_update($tid, $fields, $increaseFields);

        return $this->_update($tid, $fields, $increaseFields);
    }

    public function batchUpdateThread($tids, $fields, $increaseFields = array(), $bitFields = array())
    {
        app(PwThreadsContentDao::class)->_update($tids, $fields, $increaseFields, $bitFields);

        return $this->_batchUpdate($tids, $fields, $increaseFields);
    }

    public function deleteThread($tid)
    {
        self::destroy($tid);
        app(PwThreadsContentDao::class)->deleteThread($tid);

        return true;
    }

    public function batchDeleteThread($tids)
    {
        self::destroy($tids);
        app(PwThreadsContentDao::class)->batchDeleteThread($tids);

        return true;
    }
}