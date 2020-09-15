<?php

namespace App\Services\online\ds\dao;

use App\Services\online\ds\relation\PwUserOnline;

class PwUserOnlineDao extends PwUserOnline
{
    public function getInfo($uid)
    {
        return self::find($uid);
    }

    public function fetchInfo($uids)
    {
        return self::whereIn('uid', $uids)
            ->get();
    }

    public function getInfoList($fid, $offset, $limit)
    {
        $sql = self::whereRaw('1 = 1');

        ($fid > 0) && ($sql = $sql->where('fid', $fid));

        return $sql->orderby('created_time', 'desc');
    }

    public function replaceInfo($data)
    {
        if ($data['uid'] < 1) return false;

        return self::firstOrCreate($data);
    }

    public function deleteInfo($uid)
    {
        return self::destroy($uid);
    }

    public function deleteInfos($uids)
    {
        return self::destroy($uids);

    }

    public function deleteByLastTime($lasttime)
    {
        return self::where('modify_time', '<', $lasttime)
            ->delete();
    }

    public function getOnlineCount($fid, $tid)
    {
        $sql = self::whereRaw('1 = 1');

        if ($fid > 0) {
            $sql = $sql->where('fid', $fid);
        }
        if ($tid > 0) {
            $sql = $sql->where('tid', $tid);
        }

        return $sql->count();
    }
}

?>