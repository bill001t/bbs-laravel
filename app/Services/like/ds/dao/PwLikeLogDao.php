<?php

namespace App\Services\like\ds\dao;

use App\Services\like\ds\relation\PwLikeLog;
use DB;

class PwLikeLogDao extends PwLikeLog
{
    public function getInfo($logid)
    {
        return self::find($logid);
    }

    public function fetchInfo($logids)
    {
        return self::whereIn('logid', $logids)
            ->get();
    }

    public function getInfoByUidLikeid($uid, $likeid)
    {
        return self::where('uid', $uid)
            ->where('likeid', $likeid)
            ->first();
    }

    public function getInfoList($uids, $offset, $limit)
    {
        return self::whereIn('uid', $uids)
            ->orderby('logid', 'desc')
            ->paginate($limit);
    }

    public function getLikeCount($uid)
    {
        return self::where('uid', $uid)
            ->count();
    }

    public function getLikeidCount($likeid, $time)
    {
        return DB::SELECT('SELECT COUNT(*) AS count FROM (SELECT * FROM ' . $this->table . ' WHERE likeid = ?) AS tmpTable WHERE created_time > ?', [$likeid, $time]);
    }

    public function addInfo($data)
    {
        return self::create($data);
    }

    public function updateInfo($logid, $data)
    {
        return self::where('logid', $logid)
            ->update($data);
    }


    public function updateReplyCount($logid)
    {
        return self::where('logid', $logid)
            ->increment('reply_count');
    }

    public function deleteInfo($logid)
    {
        return self::destroy($logid);
    }

}

?>