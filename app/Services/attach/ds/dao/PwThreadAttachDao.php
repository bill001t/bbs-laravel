<?php

namespace App\Services\attach\ds\dao;

use App\Services\attach\ds\relation\ThreadAttach;

class PwThreadAttachDao extends ThreadAttach
{
    public function getAttach($aid)
    {
        return self::find($aid);
    }

    public function fetchAttach($aids)
    {
        return self::whereIn('aid', $aids)
            ->get();
    }

    public function getAttachByTid($tid, $pids)
    {
        return self::where('tid', $tid)
            ->whereIn('pid', $pids)
            ->get();
    }

    public function getTmpAttachByUserid($userid)
    {
        return self::where('tid', 0)
            ->where('pid', 0)
            ->where('created_userid', $userid)
            ->get();
    }

    public function countType($tid, $pid, $type)
    {
        return self::where('tid', $tid)
            ->where('pid', $pid)
            ->where('type', $type)
            ->count();
    }

    public function fetchAttachByTid($tids)
    {
        return self::whereIn('tid', $tids)
            ->get();
    }

    public function fetchAttachByTidAndPid($tids, $pids)
    {
        return self::whereIn('tid', $tids)
            ->whereIn('pid', $pids)
            ->get();
    }

    public function fetchAttachByTidsAndPid($tids, $pid)
    {
        return self::whereIn('tid', $tids)
            ->where('pid', $pid)
            ->get();
    }

    public function addAttach($fields)
    {
        return self::create($fields);
    }

    public function updateAttach($aid, $fields, $increaseFields = array())
    {
        foreach ($increaseFields as $k => $v) {
            self::where('aid', $aid)
                ->increment($k, $v);
        }

        return self::where('aid', $aid)
            ->update($fields);
    }

    public function updateFid($fid, $tofid)
    {
        return self::where('fid', $fid)
            ->update(['fid' => $tofid]);
    }

    public function batchUpdateAttach($aids, $fields, $increaseFields = array())
    {
        foreach ($increaseFields as $k => $v) {
            self::whereIn('aid', $aids)
                ->increment($k, $v);
        }

        return  self::whereIn('aid', $aids)
            ->update($fields);
    }

    public function batchUpdateFidByTid($tids, $fid)
    {
        return self::whereIn('tid', $tids)
            ->update(['fid' => $fid]);
    }

    public function deleteAttach($aid)
    {
        return self::destroy($aid);
    }

    public function batchDeleteAttach($aids)
    {
        return self::destroy($aids);
    }
}