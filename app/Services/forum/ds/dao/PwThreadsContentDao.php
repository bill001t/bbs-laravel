<?php
namespace App\Services\forum\ds\dao;

use App\Services\forum\ds\relation\threadsContent;
use App\Services\forum\ds\traits\threadsContentTrait;

class PwThreadsContentDao extends threadsContent
{
    use threadsContentTrait;

    public function getThread($tid)
    {
        return threadsContent::find($tid);
    }

    public function fetchThread($tids)
    {
        return threadsContent::whereIn('tid', $tids);
    }

    public function getThreadByFid($fid, $perpage)
    {
        return threadsContent::where('fid', $fid)
            ->paginate($perpage);
    }

    public function getThreadByUid($uid, $perpage)
    {
        return threadsContent::where('uid', $uid)
            ->paginate($perpage);
    }

    public function getThreadsByFidAndUids($fid, $uids, $perpage)
    {
        return threadsContent::where('fid', $fid)
            ->whereIn('uid', $uids)
            ->paginate($perpage);
    }

    public function addThread($fields)
    {
        return threadsContent::create($fields);
    }

    public function updateThread($tid, $fields, $increaseFields = array(), $bitFields = array())
    {
        return $this->_update($tid, $fields, $increaseFields);
    }

    public function batchUpdateThread($tids, $fields, $increaseFields = array(), $bitFields = array())
    {
        return $this->_batchUpdate($tids, $fields, $increaseFields);
    }

    public function deleteThread($tid)
    {
        return threadsContent::destroy($tid);
    }

    public function batchDeleteThread($tids)
    {
        return threadsContent::destroy($tids);
    }
}