<?php

namespace App\Services\attention\ds\dao;

use App\Services\attention\ds\relation\PwAttention;
use DB;

class PwAttentionDao extends PwAttention
{
    public function get($uid, $touid)
    {
        return self::where('uid', $uid)
            ->where('touid', $touid)
            ->first();
    }

    public function add($fields)
    {
        return self::create($fields);
    }

    public function _delete($uid, $touid)
    {
        return self::where('uid', $uid)
            ->where('touid', $touid)
            ->delete();
    }

    public function getFans($uid, $limit, $offset)
    {
        return self::where('touid', $uid)
            ->orderby('created_time', 'desc')
            ->paginate($limit);
    }

    public function fetchFans($uid, $touids)
    {
        return self::whereIn('uid', $touids)
            ->where('touid', $uid)
            ->get();
    }

    public function fetchFansByUids($uids, $limit, $offset)
    {
        return self::whereIn('uid', $uids)
            ->paginate($limit);
    }

    public function getFollows($uid, $limit, $offset)
    {
        return self::where('uid', $uid)
            ->orderby('created_time', 'desc')
            ->paginate($limit);
    }

    public function fetchFollows($uid, $touids)
    {
        return self::where('uid', $uid)
            ->whereIn('touid', $touids)
            ->get();
    }

    public function countFollowToFollow($uid, $touid)
    {
        return DB::SELECT('SELECT COUNT(*) AS sum FROM ' . $this->table . ' a LEFT JOIN ' . $this->table . ' b ON a.touid=b.uid WHERE a.uid=? AND b.touid=?', [$uid, $touid]);
    }

    public function getFollowToFollow($uid, $touid, $limit)
    {
        return DB::SELECT('SELECT a.touid FROM ' . $this->table . ' a LEFT JOIN ' . $this->table . ' b ON a.touid=b.uid WHERE a.uid=? AND b.touid=? ORDER BY b.created_time DESC limit ?', [$uid, $touid, $limit]);
    }

    public function getFriendsByUid($uid)
    {
        return DB::select('SELECT a.uid,b.touid as recommend_uid,b.uid AS same_uid FROM ' . $this->table . ' a left join ' . $this->table . ' b ON a.touid = b.uid  where a.uid =? GROUP BY recommend_uid, same_uid', [$uid]);
    }

    public function fetchFriendsByUids($uids)
    {
        return DB::select("SELECT uid, group_concat( touid SEPARATOR ',' ) AS touids FROM ' . $this->table . ' WHERE uid IN (?) GROUP BY uid", [implode(',', $uids)]);
    }
}