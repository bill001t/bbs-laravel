<?php

namespace App\Services\forum\ds\dao;

use App\Services\forum\ds\relation\threads;
use App\Services\forum\ds\traits\threadsTrait;
use DB;

class PwThreadsExpandDao extends threads
{
    use threadsTrait;

    public function getThreadByFidOverTime($fid, $lastpostTime, $limit, $offset)
    {
        return threads::where('fid', $fid)
            ->where('disabled', 0)
            ->where('lastpost_time', '>', $lastpostTime)
            ->orderby('lastpost_time', 'asc')
            ->paginate($limit);
    }

    public function getThreadByFidUnderTime($fid, $lastpostTime, $limit, $offset)
    {
        return threads::where('fid', $fid)
            ->where('disabled', 0)
            ->where('lastpost_time', '<', $lastpostTime)
            ->orderby('lastpost_time', 'desc')
            ->paginate($limit);
    }

    public function fetchThreadByUid($uids)
    {
        return threads::whereIn('created_userid', $uids)
            ->get();
    }

    public function countUserThreadByFidAndTime($fid, $time, $limit)
    {
        return threads::select(DB::raw('created_userid,COUNT(*) AS count'))
            ->where('fid', $fid)
            ->where('disabled', 0)
            ->where('created_time', '>', $time)
            ->groupby('created_userid')
            ->orderby('count', 'desc')
            ->take($limit);
    }

    public function countThreadsByFid()
    {
        return threads::select(DB::raw('fid,COUNT(*) AS sum'))
            ->where('disabled', 0)
            ->groupby('fid')
            ->get();
    }

    /**
     * 根据uid统计审核和未审核的帖子
     *
     * @param int $uid
     * @return int
     */
    public function countDisabledThreadByUid($uid)
    {
        return threads::where('disabled', '<', 2)
            ->where('created_userid', $uid)
            ->count();
    }

    /**
     * 根据uid获取审核和未审核的帖子
     *
     * @param int $uid
     * @param int $limit
     * @param int $offset
     * @return int
     */
    public function getDisabledThreadByUid($uid, $limit, $offset)
    {
        return threads::where('disabled', '<', 2)
            ->where('created_userid', $uid)
            ->orderby('created_time', 'desc')
            ->paginate($limit);
    }

}