<?php

namespace App\Services\like\ds\dao;

use App\Services\like\ds\relation\PwLikeSource;
use DB;

class PwLikeSourceDao extends PwLikeSource
{
    public function getSource($sid)
    {
        return self::find($sid);
    }

    public function getSourceByAppAndFromid($fromapp, $fromid)
    {
        return self::where('from_app', $fromapp)
            ->where('fromid', $fromid)
            ->first();
    }

    public function fetchSource($sids)
    {
        return self::whereIn('sid', $sids)
            ->get();
    }

    public function addSource($data)
    {
        return self::create($data);
    }

    public function deleteSource($sid)
    {
        return self::destroy($sid);
    }

    public function updateSource($sid, $data)
    {
        return self::where('sid', $sid)
            ->update($data);
    }
}

?>