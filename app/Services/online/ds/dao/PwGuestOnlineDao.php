<?php

namespace App\Services\online\ds\dao;

use App\Services\online\ds\relation\PwGuestOnline;

class PwGuestOnlineDao extends PwGuestOnline
{
    public function getInfo($ip, $createdTime)
    {
        return self::where('ip', $ip)
            ->where('created_time', $createdTime)
            ->first();
    }

    public function fetchInfo($ip)
    {
        return self::where('ip', $ip)
            ->get();
    }

    public function replaceInfo($data)
    {
        if (!$data['ip'] || !$data['created_time']) return false;

        return self::firstOrCreate($data);
    }

    public function deleteInfo($ip, $createdTime)
    {
        return self::where('ip', $ip)
            ->where('created_time', $createdTime)
            ->delete();
    }

    public function deleteInfos($ip)
    {
        return self::where('ip', $ip)
            ->delete();
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