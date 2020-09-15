<?php

namespace App\Services\forum\ds\dao;

use App\Services\forum\ds\relation\threads;
use App\Services\forum\ds\traits\threadsTrait;
use DB;

class PwThreadsDao extends threads
{
    use threadsTrait;

    public function getThread($tid)
    {
        return threads::find($tid);
    }

    public function fetchThread($tids)
    {
        return threads::whereIn('tid', $tids)
            ->get();
    }

    public function getThreadByFid($fid, $perpage)
    {
        return threads::where('fid', $fid)
            ->where('disabled', 0)
            ->orderby('lastpost_time', 'desc')
            ->paginate($perpage);
    }

    public function fetchThreadByTid($tids, $perpage)
    {
        return threads::whereIn('tid', $tids)
            ->where('disabled', 0)
            ->orderby('special_sort', 'desc')
            ->orderby('lastpost_time', 'desc')
            ->paginate($perpage);
    }

    public function countPosts($fid)
    {
        return threads::where('fid', $fid)
            ->where('disabled', 0)
            ->select(DB::raw('COUNT(*) AS topics,SUM( replies ) AS replies'))
            ->first();
    }

    public function getThreadByFidAndType($fid, $type, $limit, $start)
    {
        return threads::where('fid', $fid)
            ->where('disabled', 0)
            ->where('topic_type', $type)
            ->orderby('lastpost_time', 'desc')
            ->paginate($limit);
    }

    public function countThreadByFidAndType($fid, $type)
    {
        return threads::where('fid', $fid)
            ->where('topic_type', $type)
            ->where('disabled', 0)
            ->count();
    }

    public function countThreadByUid($uid)
    {
        return threads::where('created_userid', $uid)
            ->where('disabled', 0)
            ->count();
    }

    public function getThreadByUid($uid, $limit, $offset)
    {
        return threads::where('created_userid', $uid)
            ->where('disabled', 0)
            ->orderby('created_time', 'desc')
            ->paginate($limit);
    }

    public function getThreadsByFidAndUids($fid, $uids, $limit, $offset)
    {
        return threads::whereIn('created_userid', $uids)
            ->where('fid', $fid)
            ->where('disabled', 0)
            ->paginate($limit);
    }

    public function addThread($fields)
    {
        return threads::create($fields);
    }

    public function updateThread($tid, $fields, $increaseFields = array(), $bitFields = array())
    {
        return $this->_update($tid, $fields, $increaseFields, $bitFields);
    }

    public function batchUpdateThread($tids, $fields, $increaseFields = array(), $bitFields = array())
    {
        return $this->_batchUpdate($tids, $fields, $increaseFields, $bitFields);
    }

    public function revertTopic($tids)
    {
        return DB::update($this->_bindSql('UPDATE %s SET disabled=ischeck^1 WHERE tid IN (?)', $this->table, implode(',', $tids)));
    }

    public function deleteThread($tid)
    {
        return threads::destroy($tid);
    }

    public function batchDeleteThread($tids)
    {
        return threads::whereIn('tid', $tids)
            ->delete();
    }
}